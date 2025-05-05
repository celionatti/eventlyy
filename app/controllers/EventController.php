<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** EventController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Event;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\Category;

use celionatti\Bolt\Pagination\Pagination;

class EventController extends Controller
{
    public function events(Request $request, Response $reponse)
    {
        $this->view->setTitle("Events");

        $event = new Event();
        $category = new Category();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.status = :status GROUP BY events.event_id ORDER BY events.created_at DESC";

        $events = $event->rawPaginate($sql, ['status' => 'open'], $page, 12);

        $pagination = new Pagination($events['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'events' => $events['data']['result'],
            'count' => $events['data']['count'],
            'categories' => $category->getActiveCategories(),
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("events", $view);
    }

    public function event(Request $request, $id)
    {
        $event = new Event();
        $ticket = new Ticket();

        $data = $event->find($id)->toArray();
        $tickets = $ticket->status_tickets($id);

        $this->view->setTitle("{$data['name']}");

        $ticketOptions = [];
        foreach ($tickets as $tix) {
            $ticketOptions[$tix['ticket_id']] = ucfirst($tix['type']) . " - " . formatCurrency($tix['price']);
        }

        $view = [
            'errors' => getFormMessage(),
            'event' => $data,
            'socials' => isset($data['socials']) ? explode(',', $data['socials']) : [],
            'recents' => $event->other_events($id),
            'tickets' => $tickets,
            'ticketOpts' => $ticketOptions,
            'quantityOpts' => [
                '1' => '1 Ticket',
                '2' => '2 Tickets',
                '3' => '3 Tickets',
                '4' => '4 Tickets',
                '5' => '5 Tickets',
            ],
        ];

        $this->view->render("event", $view);
    }

    public function categories(Request $request, $category_id)
    {
        $this->view->setTitle("Events");

        $event = new Event();
        $category = new Category();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.status = :status AND events.category = :category_id GROUP BY events.event_id ORDER BY events.created_at DESC";

        $events = $event->rawPaginate($sql, ['status' => 'open', 'category_id' => $category_id], $page, 12);

        $pagination = new Pagination($events['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'filter' => $category->findBy(['category_id' => $category_id])->toArray(),
            'events' => $events['data']['result'],
            'count' => $events['data']['count'],
            'categories' => $category->getActiveCategories(),
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("events-categories", $view);
    }

    public function events_search(Request $request)
    {
        $search = $request->get("query");

        $this->view->setTitle("Search Events: {$search}");

        $event = new Event();

        // $sql = "SELECT * FROM events WHERE name LIKE '%$search%' OR description LIKE '%$search%' OR tags LIKE '%$search%' AND status = :status";

        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.name LIKE '%$search%' OR events.description LIKE '%$search%' OR events.tags LIKE '%$search%' AND events.status = :status GROUP BY events.event_id ORDER BY events.created_at DESC";

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $events = $event->rawPaginate($sql, ['status' => "open"], $page, 12);

        $pagination = new Pagination($events['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'events' => $events['data']['result'],
            'count' => $events['data']['count'],
            'search' => $search,
        ];

        $this->view->render("events-search", $view);
    }
}