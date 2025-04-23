<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** TicketController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Event;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\TicketUser;
use PhpStrike\app\models\Transaction;
use celionatti\Bolt\Illuminate\Utils\StringGenerator;
use celionatti\Bolt\Helpers\Funds\FeePercentage;
use PhpStrike\app\services\PdfGenerator;

class TicketController extends Controller
{
    public function onConstruct(): void
    {
        $this->setCurrentUser(auth_user());
    }

    public function contact(Request $request, $event_id)
    {
        $event  = new Event();
        $ticket = new Ticket();
        $feePercentage = new FeePercentage();

        $ticket_id = $request->get("ticket_id");
        $quantity = $request->get("quantity");

        if(!isset($ticket_id) || empty($ticket_id)) {
            toast("info", "Ticket Not Available!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
        }

        if(!isset($quantity) || empty($quantity)) {
            toast("info", "Pick ticket Quantity!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
        }

        $ticket_data = $ticket->find($ticket_id)->toArray();

        if($ticket_data['quantity'] === 0) {
            toast("info", "Ticket Not Available!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
        }

        if($quantity > $ticket_data['quantity']) {
            toast("info", "{$ticket_data['quantity']} {$ticket_data['type']} Ticket Left!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
        }

        /** Check for free tickets */
        if ((float) $ticket_data['price'] === 0.00 || strtolower($ticket_data['type']) === "free") {
            redirect(URL_ROOT . "/events/tickets/{$event_id}/free?ticket_id={$ticket_id}&quantity={$quantity}");
            exit;
        }

        $total_price = ceil($ticket_data['price'] * $quantity);
        $overallPrice = $feePercentage->calculateWithFlatProfit($total_price, 100);

        $view = [
            'errors' => getFormMessage(),
            'event' => $event->find($event_id)->toArray(),
            'ticket' => $ticket_data,
            'overall' => $overallPrice,
            'quantity' => $quantity,
            'contact' => retrieveSessionData('contact_data'),
        ];

        unsetSessionArrayData(['contact_data']);

        $this->view->render("checkout/contact", $view);
    }

    public function insert_contact(Request $request, $event_id)
    {
        if ("POST" !== $request->getMethod()) {
            return;
        }

        $transaction = new Transaction();
        $ticket = new Ticket();
        $ticketUser = new TicketUser();

        $refGenerator = new StringGenerator('REF', 8);
        $ticketGenerator = new StringGenerator('EVENTLYY', 8);

        $rules = [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required',
            'phone' => 'required',
        ];

        $attributes = $request->loadData();
        $attributes['ticket_id'] = $request->get("ticket_id");
        $attributes['quantity'] = $request->get("quantity");
        $attributes['event_id'] = $event_id;

        $ticketData = $ticket->findBy(['ticket_id' => $attributes['ticket_id']]);

        if (!$ticketData) {
            toast("error", "Ticket not found.");
            redirect(URL_ROOT . "/events/view/{$event_id}");
            return;
        }

        $ticketData = $ticketData->toArray();

        if ((int) $ticketData['quantity'] === 0) {
            toast("info", "Ticket Not Available!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
            return;
        }

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('contact_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/events/tickets/{$event_id}/contact?ticket_id={$request->get("ticket_id")}&quantity={$request->get("quantity")}");
            return;
        }

        $attributes['transaction_id'] = bv_uuid();
        $attributes['user_id'] = $this->currentUser['user_id'] ?? null;
        $attributes['reference_id'] = $refGenerator->generateCode();
        $attributes['token'] = $ticketGenerator->generateCode();

        $newTransaction = $transaction->create($attributes);

        if (!$newTransaction) {
            toast("error", "Transaction Process Failed.");
            redirect(URL_ROOT . "/events/tickets/{$event_id}/contact?ticket_id={$request->get("ticket_id")}&quantity={$request->get("quantity")}");
            return;
        }

        $newData = $transaction->findBy(['id' => $newTransaction->id]);

        toast("success", "Transaction Process Started!");
        redirect(URL_ROOT . "/events/tickets/{$event_id}/payment/{$newData->transaction_id}");
    }

    public function payment(Request $request, $event_id, $payment_id)
    {
        $this->view->setTitle("Payment");

        $event  = new Event();
        $transaction = new Transaction();

        $view = [
            'errors' => getFormMessage(),
            'event' => $event->find($event_id)->toArray(),
            'transaction' => $transaction->find($payment_id)->toArray(),
        ];

        $this->view->render("checkout/payment", $view);
    }
}