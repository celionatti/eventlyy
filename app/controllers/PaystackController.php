<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** PaystackController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Transaction;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\TicketUser;
use PhpStrike\app\models\Payment;
use celionatti\Bolt\Illuminate\Utils\StringGenerator;
use PhpStrike\app\services\PdfGenerator;

use Exception;

class PaystackController extends Controller
{
    private $secretKey = null;
    private $publicKey = null;
    private $baseUrl = "";

    public function onConstruct(): void
    {
        $this->secretKey = bolt_env('PAYSTACK_SECRET_KEY');
        $this->publicKey = bolt_env('PAYSTACK_PUBLIC_KEY');
        $this->baseUrl = "https://api.paystack.co";
    }

    public function initialize()
    {
        try {
            $amount = $_POST['amount'] * 100; // Convert to kobo
            $email = $_POST['email'];
            $reference = $_POST['reference_id'];
            $transaction_id = $_POST['transaction_id'];

            $url = $this->baseUrl . '/transaction/initialize';
            $fields = [
                'email' => $email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => URL_ROOT . "/verify-payment/{$transaction_id}"
            ];

            $headers = [
                'Authorization: Bearer ' . $this->secretKey,
                'Cache-Control: no-cache',
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($result['status']) {
                // Redirect to Paystack payment page
                header('Location: ' . $result['data']['authorization_url']);
                exit;
            }
            throw new Exception('Payment initialization failed');

        } catch (Exception $e) {
            // Handle error appropriately
            return ['error' => $e->getMessage()];
        }
    }

    public function verifyPayment($id)
    {
        try {
            $reference = $_GET['reference'];

            $transaction = new Transaction();
            $ticket = new Ticket();
            $ticketUser = new TicketUser();
            $payment = new Payment();

            $transactionData = $transaction->findBy(['transaction_id' => $id])->toArray();

            if(empty($transactionData)) {
                toast("info", "Transaction Data Not Found!");
                redirect(URL_ROOT);
            }

            $url = $this->baseUrl . '/transaction/verify/' . $reference;
            $headers = [
                'Authorization: Bearer ' . $this->secretKey
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($result['status'] && $result['data']['status'] === 'success') {
                // Update transaction status
                $generator = new StringGenerator('EVENTLYY', 8);
                if($transaction->update(['reference_id' => $reference, 'status' => 'confirmed', 'token' => $generator->generateCode()], $id)) {
                    $userData = [
                        'user_id' => $transactionData['user_id'],
                        'transaction_id' => $transactionData['transaction_id'],
                        'ticket_id' => $transactionData['ticket_id'],
                        'quantity' => $transactionData['quantity'],
                        'token' => $transactionData['token'],
                    ];
                    $ticketUser->create($userData);
                    $payment->addToTransaction($transactionData['transaction_id']);
                }

                $ticket->deduct_quantity((int) $transactionData['quantity'], $transactionData['ticket_id']);
                // Redirect to success page
                toast("success", "Payment verified successfully");
                redirect(URL_ROOT . "/payment-success/{$id}");
                exit;
            }

            throw new Exception('Payment verification failed');

        } catch (Exception $e) {
            // Handle error appropriately
            return ['error' => $e->getMessage()];
        }
    }

    public function success($id)
    {
        $transaction = new Transaction();

        $transactionData = $transaction->transaction_details($id);

        $view = [
            'transaction' => $transactionData,
        ];

        $this->view->render("checkout/success", $view);
    }

    public function downloadpdf(Request $request, $id)
    {
        $transaction = new Transaction();

        $ticket = $transaction->transaction_details($id);

        $pdfGenerator = new PdfGenerator();
        $pdf = $pdfGenerator->generateTicketPdf($ticket);

        // 5. Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ticket-'.$id.'.pdf"');
        echo $pdf->Output('ticket.pdf', 'S');
        exit;
    }
}