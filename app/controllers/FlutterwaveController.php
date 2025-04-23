<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** FlutterwaveController
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

class FlutterwaveController extends Controller
{
    private $publicKey = null;
    private $secretKey = null;
    private $encryptionKey = null;
    private $baseUrl = "";

    public function onConstruct(): void
    {
        $this->publicKey = bolt_env('FLUTTERWAVE_PUBLIC_KEY');
        $this->secretKey = bolt_env('FLUTTERWAVE_SECRET_KEY');
        $this->encryptionKey = bolt_env('FLUTTERWAVE_ENCRYPTION_KEY');
        $this->baseUrl = "https://api.flutterwave.com/v3";
    }

    /**
     * Initializes a Flutterwave payment
     *
     * @param array $paymentData Payment information
     * @return array Response from Flutterwave API
     */
    public function initialize()
    {
        try {
            $transaction = new Transaction();
            $details = $transaction->transaction_details($_POST['transaction_id']);
            // Required payment data
            $data = [
                'tx_ref' => $_POST['reference'],
                'amount' => $_POST['amount'],
                'currency' => "NGN",
                'redirect_url' => URL_ROOT . "/flutterwave-verify-payment/{$details['transaction_id']}",
                'payment_options' => 'card, banktransfer, ussd',
                'customer' => [
                    'email' => $details['email'],
                    'name' => $details['first_name'] . " " . $details['last_name']
                ],
                'meta' => [
                    'price' => $_POST['amount'],
                    'product_id' => $details['transaction_id'] ?? null
                ],
                'customizations' => [
                    'title' => 'Payment for '. $details['event_name'] . ' Ticket',
                    'description' => 'Payment for ' . $details['event_name'] . ' Ticket Purchase' ?? 'Payment for your ticket purchase',
                    'logo' => ''
                ]
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->baseUrl . "/payments",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $this->secretKey,
                    "Content-Type: application/json"
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data)
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            // return json_decode($response, true);
            $result = json_decode($response, true);

            if (isset($result['status']) && $result['status'] === 'success') {
                // Redirect to Flutterwave payment page
                header('Location: ' . $result['data']['link']);
                exit;
            }
            throw new Exception('Payment initialization failed');
        } catch(Exception $e) {
            // Handle error appropriately
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Verifies a Flutterwave payment
     *
     * @param string $transaction_id The transaction ID from Flutterwave
     * @return array Transaction details
     */
    public function verifyPayment($transaction_id)
    {
        // $url = "https://api.flutterwave.com/v3/transactions/" . $transaction_id . "/verify";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl . "/transactions/{$transaction_id}/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->secretKey,
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ["status" => "error", "message" => $err];
        }

        return json_decode($response, true);
    }

    /**
     * Handles the payment callback
     */
    public function handleCallback($id)
    {
        if (isset($_GET['status']) && isset($_GET['tx_ref']) && isset($_GET['transaction_id'])) {
            $transaction = new Transaction();
            $ticketUser = new TicketUser();
            $ticket = new Ticket();
            $payment = new Payment();
            $transactionData = $transaction->findBy(['transaction_id' => $id])->toArray();

            if(empty($transactionData)) {
                toast("info", "Transaction Data Not Found!");
                redirect(URL_ROOT);
            }

            $status = $_GET['status'];
            $tx_ref = $_GET['tx_ref'];
            $transaction_id = $_GET['transaction_id'];

            // Verify the transaction
            $verification = $this->verifyPayment($transaction_id);

            if (isset($verification['status']) && $verification['status'] == 'success') {
                // Transaction is verified
                $amount = $verification['data']['amount'];
                $currency = $verification['data']['currency'];
                $reference = $verification['data']['flw_ref'];

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
            } else {
                // Transaction verification failed
                toast("error", "Payment verification failed");
                return [
                    "status" => "failed",
                    "message" => "Payment verification failed"
                ];
            }
        }

        return [
            "status" => "error",
            "message" => "Invalid callback parameters"
        ];
    }
}