<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminTicketController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\Event;

use celionatti\Bolt\Pagination\Pagination;

class AdminTicketController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Tickets");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser) {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT);
        }
    }

    public function manage()
    {
        $ticket = new Ticket();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $sql = "SELECT tickets.*, events.name AS event_name
                FROM tickets
                JOIN events ON tickets.event_id = events.event_id";

                $tickets = $ticket->rawPaginate($sql, [], $page, 5);
        } else {
            $sql = "SELECT tickets.*, events.name AS event_name
                    FROM tickets
                    JOIN events ON tickets.event_id = events.event_id
                    WHERE events.user_id = :user_id";

                    $tickets = $ticket->rawPaginate($sql, ['user_id' => $this->currentUser['user_id']], $page, 5);
        }

        $pagination = new Pagination($tickets['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'tickets' => $tickets['data']['result'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/tickets/manage", $view);
    }

    public function create(Request $request)
    {
        $event = new Event();

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $fetchEvents = $event->all();
        } else {
            $fetchEvents = $event->event_tickets($this->currentUser['user_id']);
        }

        $eventOptions = [];
        foreach ($fetchEvents as $event) {
            $eventOptions[$event['event_id']] = ucfirst($event['name']);
        }

        $view = [
            'errors' => getFormMessage(),
            'ticket' => retrieveSessionData('ticket_data'),
            'eventOpts' => $eventOptions,
        ];

        unsetSessionArrayData(['ticket_data']);

        $this->view->render("admin/tickets/create", $view);
    }

    public function insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create article if validation passes
            $ticket = new Ticket();

            $rules = [
                'event_id' => 'required',
                'type' => 'required',
                'details' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadDataExcept(['details']);
            $attributes['details'] = $_POST['details'];
            $attributes['ticket_id'] = bv_uuid();

            if (!$request->validate($rules, $attributes)) {
                storeSessionData('ticket_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/tickets/create");
                return; // Ensure the method exits after redirect
            }
            unset($attributes['files']);

            if ($ticket->create($attributes)) {
                // Success: Redirect to manage page
                toast("success", "Ticket Created Successfully");
                redirect(URL_ROOT . "/admin/tickets/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Ticket creation failed!']);
                redirect(URL_ROOT . "/admin/tickets/manage");
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $ticket = new Ticket();
        $event = new Event();

        $fetchEvents = $event->all();
        $eventOptions = [];
        foreach ($fetchEvents as $event) {
            $eventOptions[$event['event_id']] = ucfirst($event['name']);
        }

        $fetchData = $ticket->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'ticket' => $fetchData ?? retrieveSessionData('ticket_data'),
            'eventOpts' => $eventOptions,
        ];

        unsetSessionArrayData(['ticket_data']);

        $this->view->render("admin/tickets/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create category if validation passes
            $ticket = new Ticket();

            $fetchData = $ticket->find($id);

            if (!$fetchData) {
                toast("info", "Ticket Not Found!");
                redirect(URL_ROOT . "/admin/tickets/manage");
            }

            $rules = [
                'event_id' => 'required',
                'type' => 'required',
                'details' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadDataExcept(['details']);
            $attributes['details'] = $_POST['details'];

            if (!$request->validate($rules, $attributes)) {
                storeSessionData('ticket_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/tickets/edit/{$fetchData->ticket_id}");
                return; // Ensure the method exits after redirect
            }
            unset($attributes['files']);

            if ($ticket->update($attributes, $id)) {
                // Success: Redirect to manage page
                toast("success", "Ticket Updated Successfully");
                redirect(URL_ROOT . "/admin/tickets/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Ticket Update Process failed!']);
                redirect(URL_ROOT . "/admin/tickets/manage");
            }
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            // Proceed to create user if validation passes
            $ticket = new Ticket();

            $fetchData = $ticket->find($id);

            if (!$fetchData) {
                toast("info", "Ticket Not Found!");
                redirect(URL_ROOT . "admin/tickets/manage");
            }
            if($ticket->delete($id)) {
                toast("success", "Ticket Deleted Successfully");
                redirect(URL_ROOT . "/admin/tickets/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'User delete process failed!']);
                redirect(URL_ROOT . "/admin/tickets/manage");
            }
        }
    }
}