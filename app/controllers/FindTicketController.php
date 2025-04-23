<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** FindTicketController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;

use PhpStrike\app\models\TicketUser;

class FindTicketController extends Controller
{
    public function onConstruct(): void
    {

    }

    public function find_ticket(Request $request)
    {
        $this->view->setTitle("Find Ticket");

        $view = [
            'errors' => getFormMessage(),
        ];

        $this->view->render("pages/find", $view);
    }

    public function find(Request $request)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $token = $_POST['token'];

        $ticketUser = new TicketUser();

        $ticket = $ticketUser->findBy(['token' => $token]);

        if(!$ticket) {
            toast("info", "Ticket Not Found!");
            redirect(URL_ROOT . "/find-ticket");
        }
        redirect(URL_ROOT . "/find-ticket/{$ticket->transaction_id}");
    }

    public function ticket_details(Request $request, $id)
    {
        $this->view->setTitle("Ticket Details");

        $ticketUser = new TicketUser();

        $data = $ticketUser->ticket_details($id);

        $view = [
            'details' => $data,
        ];

        $this->view->render("pages/ticket-details", $view);
    }
}