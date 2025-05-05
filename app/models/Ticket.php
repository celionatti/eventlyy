<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Ticket Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;
use PhpStrike\app\models\Event;

class Ticket extends Model
{
    protected $primaryKey = "ticket_id";
    protected $fillable = ['ticket_id', 'event_id', 'type', 'details', 'price', 'quantity'];

    public function all_tickets()
    {
        $sql = "SELECT tickets.*, events.name AS event_name
                FROM tickets
                JOIN events ON tickets.event_id = events.event_id";

        // Assuming the query method properly handles binding and returns an associative array
        $result = $this->query($sql, [], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'];
    }

    public function status_tickets($id)
    {
        $sql = "SELECT t.* FROM tickets t JOIN events e ON t.event_id = e.event_id WHERE t.event_id = :event_id AND t.quantity > :quantity AND e.ticket_sale != 'pause' ORDER BY t.price ASC";

        // Assuming the query method properly handles binding and returns an associative array
        $result = $this->query($sql, ['event_id' => $id, 'quantity' => 0], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'];
    }

    public function tickets($id)
    {
        $sql = "SELECT t.* FROM tickets t JOIN events e ON t.event_id = e.event_id WHERE t.event_id = :event_id AND t.quantity > :quantity ORDER BY t.price ASC";

        // Assuming the query method properly handles binding and returns an associative array
        $result = $this->query($sql, ['event_id' => $id, 'quantity' => 0], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'];
    }

    public function total_sales($id)
    {
        $sql = "SELECT t.event_id, SUM(tu.quantity) AS total_sold_tickets, SUM(t.price * tu.quantity) AS total_amount FROM tickets t JOIN ticket_users tu ON t.ticket_id = tu.ticket_id WHERE t.event_id = :event_id GROUP BY t.event_id";

        $result = $this->query($sql, ['event_id' => $id], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'][0];
    }

    public function deduct_quantity(int $quantity_count, $ticket_id)
    {
        $query = "UPDATE tickets SET quantity = quantity - :quantity_count WHERE ticket_id = :ticket_id;";
        $params = [
            'quantity_count' => $quantity_count,
            'ticket_id' => $ticket_id
        ];
        return $this->query($query, $params, "assoc");
    }

    public function deleteByEventId($eventId)
    {
        $sql = "DELETE FROM tickets WHERE event_id = :event_id";
        return $this->query($sql, ['event_id' => $eventId]);
    }

    public function cleanUpOrphanedTickets()
    {
        $sql = "DELETE FROM tickets WHERE event_id NOT IN (SELECT event_id FROM events)";
        return $this->query($sql);
    }
}