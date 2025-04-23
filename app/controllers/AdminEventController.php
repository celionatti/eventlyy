<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminEventController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Event;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\Transaction;
use PhpStrike\app\models\Category;
use PhpStrike\app\models\Payment;
use PhpStrike\app\models\TicketUser;

use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;
use PhpStrike\app\services\PdfGenerator;

use celionatti\Bolt\Pagination\Pagination;

class AdminEventController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Events");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser) {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT);
        }

        // $this->handleEventStatus();
        // $this->eventCleanUp();
    }

    public function manage()
    {
        $event = new Event();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $events = $event->paginate($page, 5, [], ['created_at' => "DESC"]);
        } else {
            $events = $event->paginate($page, 5, ['user_id' => $this->currentUser['user_id']], ['created_at' => "DESC"]);
        }

        $pagination = new Pagination($events['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'events' => $events['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/events/manage", $view);
    }

    public function create(Request $request)
    {
        $event = new Event();
        $payment = new Payment();
        $categories = new Category();

        $paymentUserId = $payment->findBy(['user_id' => $this->currentUser['user_id']]);

        if(!$paymentUserId) {
            toast("info", "Please kindly add a bank details!");
            redirect(URL_ROOT . "/admin/events/manage");
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
            'upload_type' => $request->get('ut'),
            'is_highlighted' => $is_highlighted,
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("admin/events/create", $view);
    }

    public function insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create article if validation passes
            $event = new Event();
            $payment = new Payment();

            $paymentUserId = $payment->findBy(['user_id' => $this->currentUser['user_id']]);

            if(!$paymentUserId) {
                toast("info", "Please kindly add a bank details!");
                redirect(URL_ROOT . "/admin/events/manage");
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
                // 'user_id' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadDataExcept(['description']);
            $attributes['description'] = $_POST['description'];
            $attributes['event_id'] = bv_uuid();
            $attributes['user_id'] = $this->currentUser['user_id'] ?? null;

            // Get the submitted social links array
            $socialLinks = $_POST['socials'];

            // Remove empty fields and sanitize inputs
            $filteredLinks = array_filter($socialLinks, fn($link) => !empty($link));
            $safeLinks = array_map('htmlspecialchars', $filteredLinks);

            // Convert to comma-separated string
            $socialString = implode(',', $safeLinks);

            $attributes['socials'] = $socialString;

            if($request->get("ut") === "file") {
                $upload = new Upload("uploads/events");
                $event_image = $upload->uploadFile("image");
                $attributes['image'] = $event_image['file'];

                if($event_image['success']) {
                    $image = new Image();
                    $image->resize($attributes['image']);
                }
            }

            if (!$request->validate($rules, $attributes)) {
                // delete uploaded image
                if ($request->get("ut") === "file" && isset($attributes['image'])) {
                    $upload->delete($attributes['image']);
                }

                storeSessionData('event_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/events/create?ut={$request->get('ut')}");
                return; // Ensure the method exits after redirect
            }

            if ($event->create($attributes) && $event_image['success']) {
                // Success: Redirect to manage page
                toast("success", "Event Created Successfully");
                redirect(URL_ROOT . "/admin/events/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Event creation failed!']);
                redirect(URL_ROOT . "/admin/events/manage");
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $event = new Event();

        $fetchData = $event->find($id)->toArray();

        $categories = new Category();

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
                'closed' => 'Close',
                'cancelled' => 'Cancel'
            ],
            'highlightedOpts' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'links' => $defaultLinks,
            'upload_type' => $request->get('ut'),
            'is_highlighted' => $is_highlighted,
        ];

        unsetSessionArrayData(['event_data']);

        $this->view->render("admin/events/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if ("POST" !== $request->getMethod()) return;

        $event = new Event();
        $existingEvent = $event->find($id);
        if (!$existingEvent) {
            toast("error", "Event not found!");
            redirect(URL_ROOT . "/admin/events/manage");
            return;
        }

        // Validation rules
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
            // 'user_id' => 'required',
        ];

        // Load and prepare data
        $attributes = $request->loadDataExcept(['description']);
        $attributes['description'] = $_POST['description'];

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
            // delete uploaded image
            if ($request->get("ut") === "file") {
                $upload->delete($attributes['image']);
            }

            storeSessionData('event_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/admin/events/edit/{$id}?ut={$request->get('ut')}");
            return; // Ensure exit after redirect
        }

        // Update the event
        if ($event->update($attributes, $id)) {
            toast("success", "Event Updated Successfully");
            redirect(URL_ROOT . "/admin/events/manage");
        } else {
            setFormMessage(['error' => 'Event update process failed!']);
            redirect(URL_ROOT . "/admin/events/manage");
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            $event = new Event();

            if(auth_role($this->currentUser['role'], ['admin'])) {
                $fetchData = $event->findBy(['event_id' => $id]);
            } else {
                $fetchData = $event->findBy(['event_id' => $id, 'user_id' => $this->currentUser['user_id']]);
            }

            // Define the redirect URL once to avoid repetition
            $redirectUrl = URL_ROOT . "/admin/events/manage";

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
            } else {
                setFormMessage(['error' => 'Event delete process failed!']);
            }

            // Redirect back to the events management page in either case
            return redirect($redirectUrl);
        }
    }

    public function details(Request $request, $id)
    {
        $transaction = new Transaction();
        $event = new Event();

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
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $sql = "SELECT transactions.quantity, transactions.amount, transactions.token, transactions.status,
                -- events.name AS event_name, events.image, events.date_time, events.location,
                tickets.type, tickets.price
                FROM transactions
                JOIN events ON transactions.event_id = events.event_id
                JOIN tickets ON transactions.ticket_id = tickets.ticket_id
                WHERE transactions.event_id = :event_id AND transactions.status = :status";

                $transactions = $transaction->rawPaginate($sql, ['status' => 'confirmed', 'event_id' => $id], $page, 12);
        } else {
            $sql = "SELECT transactions.quantity, transactions.amount, transactions.token, transactions.status,
                -- events.name AS event_name, events.image, events.date_time, events.location,
                tickets.type, tickets.price
                FROM transactions
                JOIN events ON transactions.event_id = events.event_id
                JOIN tickets ON transactions.ticket_id = tickets.ticket_id
                WHERE transactions.event_id = :event_id AND transactions.status = :status AND events.user_id = :user_id";

                $transactions = $transaction->rawPaginate($sql, ['status' => 'confirmed', 'event_id' => $id, 'user_id' => $this->currentUser['user_id']], $page, 12);
        }

        $pagination = new Pagination($transactions['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'event' => $event->find($id)->toArray(),
            'transactions' => $transactions['data']['result'],
            'total' => $transaction->event_total($id),
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/events/transactions", $view);
    }

    public function generatepdf(Request $request, $id)
    {
        $transaction = new Transaction();

        $ticket = $transaction->transactions_details($id);

        $pdfGenerator = new PdfGenerator();
        $pdf = $pdfGenerator->generateTicketSalesListPdf($ticket);

        // 5. Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ticket-sales-'.$id.'.pdf"');
        echo $pdf->Output('ticket_sales.pdf', 'I');
        exit;
    }

    private function handleThumbnail(Request $request, $fetchData, $attributes)
    {
        $upload = new Upload("uploads/events");

        if ($request->get("ut") === "file") {
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $event_image = $upload->uploadFile("image");
                if ($event_image['success']) {
                    if (!is_null($fetchData->image)) {
                        $upload->delete($fetchData->image, true);
                    }
                    $image = new Image();
                    $image->resize($event_image['file']);
                    return $event_image['file'];
                }
                throw new Exception('Image upload failed!');
            }
            return $fetchData->image;
        }

        if ($request->get("ut") === "link") {
            if (file_exists($fetchData->image)) {
                $upload->delete($fetchData->image);
            }
            $imageValue = $_POST['image'];
            if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
                return $imageValue;
            }
            throw new Exception('Invalid image link!');
        }

        return $fetchData->image;
    }

    private function eventCleanUp()
    {
        $transaction = new Transaction();
        $ticket = new Ticket();
        $ticketUser = new TicketUser();

        $ticket->cleanUpOrphanedTickets();
        $transaction->cleanUpOrphanedTransactions();
        $ticketUser->cleanUpOrphanedUserTickets();
    }

    private function handleEventStatus()
    {
        $event = new Event();
        $transaction = new Transaction();
        $ticket = new Ticket();
        $currentDate = new \DateTime();

        // Fetch all events
        $events = $event->all();

        foreach ($events as $item) {
            $eventDate = new \DateTime($item['date_time']);
            $status = $item['status'];

            // Handle pending and cancelled events
            if (in_array($status, ['pending', 'cancelled'])) {
                if ($currentDate > $eventDate) {
                    $this->deleteEventAndTickets($item);
                    $ticket->cleanUpOrphanedTickets();
                    $transaction->cleanUpOrphanedTransactions();
                }
                continue;
            }

            // Handle open events
            if ($status === 'open') {
                if ($currentDate > $eventDate) {
                    // Close the event
                    $event->update(['status' => 'closed'], $item['event_id']);
                }
            }

            // Handle closed events
            if ($status === 'closed') {
                $gracePeriodEnd = (clone $eventDate)->modify('+10 days');
                if ($currentDate > $gracePeriodEnd) {
                    $this->deleteEventAndTickets($item);
                    $ticket->cleanUpOrphanedTickets();
                    $transaction->cleanUpOrphanedTransactions();
                }
            }
        }
    }

    private function deleteEventAndTickets(array $event)
    {
        $ticket = new Ticket();
        $upload = new Upload("uploads/events");

        // Delete associated tickets
        $ticket->deleteByEventId($event['event_id']);

        // Delete event image if exists
        if (!empty($event['image']) && file_exists($event['image'])) {
            if (!$upload->delete($event['image'])) {
                toast("error", "Failed to delete event image!");
            }
        }

        // Delete the event
        $eventModel = new Event();
        $eventModel->delete($event['event_id']);
    }
}