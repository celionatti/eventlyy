<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** OrganiserEventController
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
use PhpStrike\app\models\TicketUser;
use PhpStrike\app\models\Transaction;
use PhpStrike\app\models\Category;
use PhpStrike\app\models\Payment;
use Exception;


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
        }
        $events = $events->toArray();
        $tickets = $ticket->tickets($id);
        $total_sales = $ticket->total_sales($id);

        $view = [
            'event' => $events,
            'tickets' => $tickets,
            'total_sales' => $total_sales,
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
        $attributes['ticket_sale'] = 'pause';

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
            toast("error", "Invalid Request Method");
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
            redirect(URL_ROOT . "/organiser/events/manage");
        }
    }

    public function preview(Request $request, $id)
    {
        $event = new Event();

        $data = $event->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'event' => $data ?? retrieveSessionData('event_data'),
            'statusOpts' => [
                'open' => 'Open',
                'pending' => 'Pending',
            ],
            'saleOpts' => [
                'sell' => 'Open Sale',
                'pause' => 'Pause',
            ],
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("organiser/events/preview", $view);
    }

    public function update_preview(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }
        $event = new Event();

        // Load and validate data
        $attributes = $request->loadData();

        if ($event->update($attributes, $id)) {
            // Success: Redirect to manage page
            toast("success", "Event Created Successfully");
            redirect(URL_ROOT . "/organiser/events/manage");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Event process failed!']);
            redirect(URL_ROOT . "/organiser/events/manage");
        }
    }

    // Edit Event

    public function edit(Request $request, $id)
    {
        $event = new Event();
        $categories = new Category();

        $fetchData = $event->find($id)->toArray();

        $fetchCategories = $categories->allBy("status", "active");
        $categoryOptions = [];
        foreach ($fetchCategories as $category) {
            $categoryOptions[$category['category_id']] = ucfirst($category['name']);
        }

        $is_highlighted = $event->highlighted_count();

        // Split the social links into an array
         $socialLinks = isset($fetchData['socials']) ? explode(',', $fetchData['socials']) : [];

         $defaultLinks = [
            'twitter' => $socialLinks[0] ?? '',
            'facebook' => $socialLinks[1] ?? '',
            'instagram'  => $socialLinks[2] ?? '',
            'tiktok' => $socialLinks[3] ?? '',
        ];

        $view = [
            'errors' => getFormMessage(),
            'event' => $fetchData ?? retrieveSessionData('event_data'),
            'categoryOpts' => $categoryOptions,
            'statusOpts' => [
                'open' => 'Open',
                'pending' => 'Pending',
            ],
            'highlightedOpts' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'saleOpts' => [
                'sell' => 'Open Sale',
                'pause' => 'Pause',
            ],
            'links' => $defaultLinks,
            'upload_type' => $request->get('ut'),
            'is_highlighted' => $is_highlighted,
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("organiser/events/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }
        $event = new Event();

        $existingEvent = $event->find($id);

        if (!$existingEvent) {
            toast("error", "Event not found!");
            redirect(URL_ROOT . "/organiser/events/manage");
            return;
        }

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
        ];

        // Load and prepare data
        $attributes = $request->loadData();

        // Get the submitted social links array
        $socialLinks = $_POST['socials'];

        // Remove empty fields and sanitize inputs
        $filteredLinks = array_filter($socialLinks, fn($link) => !empty($link));
        $safeLinks = array_map('htmlspecialchars', $filteredLinks);

        // Convert to comma-separated string
        $socialString = implode(',', $safeLinks);

        $attributes['socials'] = $socialString;

        // Handle file upload if ut=file
        $attributes['image'] = $this->handleThumbnail($request, $existingEvent, $attributes);

        // Validate request data
        if (!$request->validate($rules, $attributes)) {
            storeSessionData('event_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/organiser/events/edit/{$id}");
            return; // Ensure exit after redirect
        }

        // Update the event
        if ($event->update($attributes, $id)) {
            toast("success", "Event Updated Successfully");
            redirect(URL_ROOT . "/organiser/events/details/{$id}");
        } else {
            setFormMessage(['error' => 'Event update process failed!']);
            redirect(URL_ROOT . "/organiser/events/details/{$id}");
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }

        $event = new Event();
        $ticket = new Ticket();

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $fetchData = $event->findBy(['event_id' => $id]);
        } else {
            $fetchData = $event->findBy(['event_id' => $id, 'user_id' => $this->currentUser['user_id']]);
        }

        // Define the redirect URL once to avoid repetition
        $redirectUrl = URL_ROOT . "/organiser/events/manage";

        // Check if the article exists
        if (!$fetchData) {
            toast("error", "Event Not Found!");
            return redirect($redirectUrl);
        }

        // Attempt to delete the image if it exists
        $upload = new Upload("uploads/events");
        if(file_exists($fetchData->image)) {
            if (!$upload->delete($fetchData->image)) {
                setFormMessage(['error' => 'Image delete failed!']);
            }
        }

        // Delete the event
        if ($event->delete($id)) {
            toast("success", "Event Deleted Successfully");
            $ticket->cleanUpOrphanedTickets();
        } else {
            setFormMessage(['error' => 'Event delete process failed!']);
        }
        // Redirect back to the events management page in either case
        return redirect($redirectUrl);
    }

    public function ticket_sale(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }

        $event = new Event();

        $event_data = $event->find($id)->toArray();

        if(!$event_data) {
            toast("info", "Event Data Not Found!");
            redirect(URL_ROOT . "/organiser/events/details/{$id}");
            return;
        }
        // Load and prepare data
        $attributes = $request->loadData();

        if ($event->update($attributes, $id)) {
            toast("success", "Ticket Sale Updated Successfully");
            redirect(URL_ROOT . "/organiser/events/details/{$id}");
        } else {
            toast("success", "Ticket Sale process failed!");
            redirect(URL_ROOT . "/organiser/events/details/{$id}");
        }
    }

    public function manage_ticket(Request $request, $id)
    {
        $event = new Event();
        $ticket = new Ticket();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $data = $event->find($id)->toArray();
        // $tickets = $ticket->view_tickets($id);
        $sql = "SELECT * FROM tickets WHERE event_id = :event_id ORDER BY price ASC";

        $tickets = $ticket->rawPaginate($sql, ['event_id' => $id], $page, 12);

        $pagination = new Pagination($tickets['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'event' => $data,
            'tickets' => $tickets['data']['result'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("organiser/events/tickets/manage", $view);
    }

    public function create_ticket(Request $request, $id)
    {
        $event = new Event();

        $data = $event->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'event' => $data,
            'ticket' => retrieveSessionData('ticket_data'),
        ];

        unsetSessionArrayData(['ticket_data']);

        $this->view->render("organiser/events/tickets/create", $view);
    }

    public function save_ticket(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
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
            redirect(URL_ROOT . "/organiser/events/details/{$id}/create");
            return; // Ensure the method exits after redirect
        }

        if ($ticket->create($attributes)) {
            // Success: Redirect to manage page
            toast("success", "Ticket Created Successfully");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Ticket creation process failed!']);
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
        }
    }

    public function edit_ticket(Request $request, $id, $ticket_id)
    {
        $event = new Event();
        $ticket = new Ticket();

        $data = $event->find($id)->toArray();
        $ticket_data = $ticket->findBy(['ticket_id' => $ticket_id])->toArray();

        $view = [
            'errors' => getFormMessage(),
            'event' => $data,
            'ticket' => $ticket_data ?? retrieveSessionData('ticket_data'),
        ];

        unsetSessionArrayData(['ticket_data']);

        $this->view->render("organiser/events/tickets/edit", $view);
    }

    public function update_ticket(Request $request, $id, $ticket_id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }
        $ticket = new Ticket();

        $existingTicket = $ticket->findBy(['ticket_id' => $ticket_id]);

        if (!$existingTicket) {
            toast("error", "Ticket not found!");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
            return;
        }

        $rules = [
            'type' => 'required',
            'details' => 'required',
            'price' => 'required',
            'quantity' => 'required',
        ];

        $attributes = $request->loadData();

        // Validate request data
        if (!$request->validate($rules, $attributes)) {
            storeSessionData('ticket_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket/edit/{$ticket_id}");
            return; // Ensure exit after redirect
        }

        // Update the event
        if ($ticket->update($attributes, $ticket_id)) {
            toast("success", "Ticket Updated Successfully");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
        } else {
            setFormMessage(['error' => 'Ticket update process failed!']);
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
        }
    }

    public function delete_ticket(Request $request, $id, $ticket_id)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }

        $event = new Event();
        $ticket = new Ticket();

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $event_data = $event->findBy(['event_id' => $id]);
        } else {
            $event_data = $event->findBy(['event_id' => $id, 'user_id' => $this->currentUser['user_id']]);
        }

        if($event_data) {
            $ticket_data = $ticket->findBy(['ticket_id' => $ticket_id]);
            if($ticket_data && $ticket->delete($ticket_id)) {
                toast("success", "Ticket Deleted Successfully!");
                redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
            } else {
                toast("error", "Process Failed");
                redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
            }
        } else {
            toast("error", "Event Not Found");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/ticket");
        }
    }

    public function attendees(Request $request, $id)
    {
        $event = new Event();
        $ticketUser = new TicketUser();
        $transaction = new Transaction();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $data = $event->find($id)->toArray();

        $sql = "SELECT t.transaction_id AS transactionId, t.user_id, t.first_name, t.last_name, t.email, t.created_at AS purchase_date, tk.type AS ticket_type, tu.token AS ticket_token, tu.status AS ticket_status FROM transactions t JOIN ticket_users tu ON t.transaction_id = tu.transaction_id JOIN tickets tk ON t.ticket_id = tk.ticket_id WHERE t.status = :status AND t.event_id = :event_id";

        $transactions = $transaction->rawPaginate($sql, ['status' => 'confirmed', 'event_id' => $id], $page, 12);

        $pagination = new Pagination($transactions['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'errors' => getFormMessage(),
            'event' => $data,
            'ticket_transactions' => $transactions['data']['result'],
            'ticket' => retrieveSessionData('event_data'),
            'pagination' => $pagination->render("ellipses"),
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("organiser/events/tickets/attendees", $view);
    }

    public function attendee(Request $request, $id, $attendee)
    {
        $event = new Event();
        $transaction = new Transaction();

        $data = $event->find($id)->toArray();
        $details = $transaction->ticket_details($attendee);

        $view = [
            'event' => $data,
            'info' => $details,
        ];

        $this->view->render("organiser/events/tickets/attendee_details", $view);
    }

    public function attendee_status(Request $request, $id, $attendee)
    {
        if("POST" !== $request->getMethod()) {
            toast("error", "Invalid Request Method");
            return;
        }

        $ticketUser = new TicketUser();

        $ticket_data = $ticketUser->findBy(['transaction_id' => $attendee])->toArray();

        if(!$ticket_data) {
            toast("info", "Ticket Data Not Found!");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/attendee/{$attendee}");
            return;
        }
        // Load and prepare data
        $attributes = $request->loadData();

        if ($ticketUser->update($attributes, $attendee, "transaction_id")) {
            toast("success", "Ticket Status Updated Successfully");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/attendee/{$attendee}");
        } else {
            toast("success", "Ticket Status process failed!");
            redirect(URL_ROOT . "/organiser/events/details/{$id}/attendee/{$attendee}");
        }
    }

    private function handleThumbnail(Request $request, $fetchData, $attributes)
    {
        $upload = new Upload("uploads/events");

        // Check if a file was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Process the uploaded file
            $event_image = $upload->uploadFile("image");

            if ($event_image['success']) {
                // Delete the old image if it exists
                if (!is_null($fetchData->image) && file_exists($fetchData->image)) {
                    $upload->delete($fetchData->image, true);
                }

                // Resize the new image
                $image = new Image();
                $image->resize($event_image['file']);

                return $event_image['file'];
            }

            throw new Exception('Image upload failed!');
        }

        // If no new file was uploaded, keep the existing image
        return $fetchData->image;
    }
}