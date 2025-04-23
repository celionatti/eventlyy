<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** OrganiserController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;
use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;
use celionatti\Bolt\Pagination\Pagination;

use PhpStrike\app\models\Event;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\Category;
use PhpStrike\app\models\Payment;


class OrganiserEventController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("organiser");
        $this->view->setTitle("Manage Events");
        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] === "user") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/auth/login");
        }
    }

    public function manage()
    {
        $event = new Event();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $events = $event->paginate($page, 5, ['user_id' => $this->currentUser['user_id']], ['created_at' => "DESC"]);

        $pagination = new Pagination($events['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'events' => $events['data'],
            'count' => count($events['data']),
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("organiser/events/manage", $view);
    }

    public function details(Request $request, $id)
    {
        $event = new Event();
        $ticket = new Ticket();

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $events = $event->findBy(['event_id' => $id]);
        } else {
            $events = $event->findBy(['event_id' => $id, 'user_id' => $this->currentUser['user_id']]);
        }

        if(!$events) {
            toast("error", "Cant access Event!");
            back();
        } else {
            $events = $events->toArray();
            $tickets = $ticket->tickets($id);
        }

        $view = [
            'event' => $events,
            'tickets' => $tickets,
        ];

        $this->view->render("organiser/events/details", $view);
    }

    public function create(Request $request)
    {
        $event = new Event();
        $payment = new Payment();
        $categories = new Category();

        $paymentUserId = $payment->findBy(['user_id' => $this->currentUser['user_id']]);

        if(!$paymentUserId) {
            toast("info", "Please kindly add a bank details!");
            redirect(URL_ROOT . "/organiser/events/manage");
        }

        $fetchCategories = $categories->allBy("status", "active");
        $categoryOptions = [];
        foreach ($fetchCategories as $category) {
            $categoryOptions[$category['category_id']] = ucfirst($category['name']);
        }

        $is_highlighted = $event->highlighted_count();

        $view = [
            'errors' => getFormMessage(),
            'event' => retrieveSessionData('event_data'),
            'categoryOpts' => $categoryOptions,
            'highlightedOpts' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'is_highlighted' => $is_highlighted,
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("organiser/events/create", $view);
    }

    public function insert(Request $request)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }
        $event = new Event();

        $rules = [
            'name' => 'required',
            'tags' => 'required',
            'category' => 'required',
            'image' => 'required',
            'description' => 'required',
            'location' => 'required',
            'date_time' => 'required',
            'status' => 'required',
            'phone' => 'required',
            'mail' => 'required',
            'user_id' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadData();
        $attributes['event_id'] = bv_uuid();
        $attributes['user_id'] = $this->currentUser['user_id'] ?? null;
        $attributes['status'] = 'pending';

        // Get the submitted social links array
        $socialLinks = $_POST['socials'];

        // Remove empty fields and sanitize inputs
        $filteredLinks = array_filter($socialLinks, fn($link) => !empty($link));
        $safeLinks = array_map('htmlspecialchars', $filteredLinks);

        // Convert to comma-separated string
        $socialString = implode(',', $safeLinks);

        $attributes['socials'] = $socialString;

        $upload = new Upload("uploads/events");
        $event_image = $upload->uploadFile("image");
        $attributes['image'] = $event_image['file'];

        if($event_image['success']) {
            $image = new Image();
            $image->resize($attributes['image']);
        }

        if (!$request->validate($rules, $attributes)) {
            // delete uploaded image
            if (isset($attributes['image'])) {
                $upload->delete($attributes['image']);
            }

            storeSessionData('event_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/organiser/events/create");
            return; // Ensure the method exits after redirect
        }

        $eventId = $event->create($attributes);

        if ($eventId && $event_image['success']) {
            // Success: Redirect to manage page
            toast("success", "Event Created Successfully");
            redirect(URL_ROOT . "/organiser/events/create/ticket/{$eventId->event_id}");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Event creation failed!']);
            redirect(URL_ROOT . "/organiser/events/manage");
        }
    }

    public function ticket(Request $request, $id)
    {
        $event = new Event();

        $data = $event->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'ticket' => retrieveSessionData('ticket_data'),
            'event' => $data,
        ];

        unsetSessionArrayData(['ticket_data']);

        $this->view->render("organiser/events/ticket", $view);
    }

    public function insert_ticket(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }
        $ticket = new Ticket();

        $rules = [
            'event_id' => 'required',
            'type' => 'required',
            'details' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ];

        $attributes = $request->loadData();
        $attributes['ticket_id'] = bv_uuid();
        $attributes['event_id'] = $id;

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('ticket_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/organiser/events/create/ticket/{$id}");
            return; // Ensure the method exits after redirect
        }

        if ($ticket->create($attributes)) {
            // Success: Redirect to manage page
            toast("success", "Ticket Created Successfully");
            redirect(URL_ROOT . "/organiser/events/create/ticket/{$id}");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Ticket creation failed!']);
            redirect(URL_ROOT . "/organiser/tickets/manage");
        }
    }

    public function preview(Request $request)
    {
        $event = new Event();
        $payment = new Payment();
        $categories = new Category();

        $paymentUserId = $payment->findBy(['user_id' => $this->currentUser['user_id']]);

        if(!$paymentUserId) {
            toast("info", "Please kindly add a bank details!");
            redirect(URL_ROOT . "/organiser/events/manage");
        }

        $fetchCategories = $categories->allBy("status", "active");
        $categoryOptions = [];
        foreach ($fetchCategories as $category) {
            $categoryOptions[$category['category_id']] = ucfirst($category['name']);
        }

        $is_highlighted = $event->highlighted_count();

        $view = [
            'errors' => getFormMessage(),
            'event' => retrieveSessionData('event_data'),
            'categoryOpts' => $categoryOptions,
            'statusOpts' => [
                'open' => 'Open',
                'pending' => 'Pending',
                'closed' => 'Close',
                'cancelled' => 'Cancel'
            ],
            'highlightedOpts' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'is_highlighted' => $is_highlighted,
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("organiser/events/create", $view);
    }
}