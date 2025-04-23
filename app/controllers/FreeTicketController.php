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
use celionatti\Bolt\Helpers\Funds\PaystackPercentage;
use PhpStrike\app\services\PdfGenerator;

class FreeTicketController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setTitle("Tickets");
        $this->setCurrentUser(auth_user());
    }

    public function free(Request $request, $event_id)
    {
        $event  = new Event();
        $ticket = new Ticket();

        $ticket_id = $request->get("ticket_id");
        $quantity = $request->get("quantity");

        if(!isset($quantity) || empty($quantity)) {
            toast("info", "Pick ticket Quantity!");
            redirect(URL_ROOT . "/events/view/{$event_id}");
        }

        $ticket_data = $ticket->find($ticket_id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'event' => $event->find($event_id)->toArray(),
            'ticket' => $ticket_data,
            'quantity' => $quantity,
            'contact' => retrieveSessionData('contact_data'),
        ];

        unsetSessionArrayData(['contact_data']);

        $this->view->render("checkout/free-contact", $view);
    }

    public function insert_free(Request $request, $event_id)
    {
        if ("POST" !== $request->getMethod()) {
            return;
        }

        $ticket = new Ticket();
        $ticketUser = new TicketUser();

        $ticketGenerator = new StringGenerator('EVENTLYY', 8);

        $rules = [
            'assign_to' => 'required|string',
        ];

        $attributes = $request->loadData();
        $assignTo = $_POST['assign_to'];
        $attributes['ticket_id'] = $request->get("ticket_id");
        $attributes['quantity'] = (int)$request->get("quantity");

        // Remove empty fields and sanitize inputs
        $filteredNames = array_filter($assignTo, fn($name) => !empty($name));
        $safeNames = array_map('htmlspecialchars', $filteredNames);

        // Convert to comma-separated string
        $nameString = implode(',', $safeNames);

        $attributes['assign_to'] = $nameString;

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
            redirect(URL_ROOT . "/events/tickets/{$event_id}/free?ticket_id={$request->get("ticket_id")}&quantity={$request->get("quantity")}");
            return;
        }

        $attributes['transaction_id'] = bv_uuid();
        $attributes['user_id'] = $this->currentUser['user_id'] ?? null;
        $attributes['token'] = $ticketGenerator->generateCode();

        $newTicketUser = $ticketUser->create($attributes);

        if (!$newTicketUser) {
            toast("error", "Ticket Process Failed.");
            redirect(URL_ROOT . "/event/tickets/{$event_id}/free?ticket_id={$request->get("ticket_id")}&quantity={$request->get("quantity")}");
            return;
        }

        $newData = $ticketUser->findBy(['id' => $newTicketUser->id]);

        $ticket->deduct_quantity((int) $request->get("quantity"), $newData->ticket_id);

        toast("success", "Thanks! Save Your invoice please.");
        redirect(URL_ROOT . "/ticket-details/{$newData->transaction_id}");
    }

    public function details(Request $request, $id)
    {
        $ticketUser = new TicketUser();

        $data = $ticketUser->ticket_details($id);

        $view = [
            'details' => $data,
        ];

        $this->view->render("checkout/details", $view);
    }

    public function downloadpdf(Request $request, $id)
    {
        $ticketUser = new TicketUser();

        $ticket = $ticketUser->ticket_details($id);

        $pdfGenerator = new PdfGenerator();
        $pdf = $pdfGenerator->generateTicketPdf($ticket);

        // 5. Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ticket-'.$id.'.pdf"');
        echo $pdf->Output('ticket.pdf', 'S');
        exit;
    }
}