<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== TicketUser Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class TicketUser extends Model
{
    protected $table = "ticket_users";
    protected $fillable = ['user_id', 'transaction_id', 'ticket_id', 'quantity', 'token', 'assign_to'];

    public function ticket_details($id)
    {
        $sql = "SELECT ticket_users.*,
                tickets.type, tickets.price, tickets.event_id AS eventID,
                events.name AS event_name, events.image, events.date_time, events.location
                FROM ticket_users
                JOIN tickets ON ticket_users.ticket_id = tickets.ticket_id
                JOIN events ON tickets.event_id = events.event_id
                WHERE ticket_users.transaction_id = :transaction_id";

        $result = $this->query($sql, ['transaction_id' => $id], "assoc");

        // Handle empty results more explicitly
        if (empty($result['result'])) {
            return [];
        }

        return $result['result'][0];
    }

    public function tickets_info($user_id)
    {
        $sql = "SELECT ticket_users.*,
                tickets.type, tickets.event_id AS eventID,
                events.name AS event_name, events.date_time, events.location
                FROM ticket_users
                JOIN tickets ON ticket_users.ticket_id = tickets.ticket_id
                JOIN events ON tickets.event_id = events.event_id
                WHERE ticket_users.user_id = :user_id";

        $result = $this->query($sql, ['user_id' => $user_id], "assoc");

        // Handle empty results more explicitly
        if (empty($result['result'])) {
            return [];
        }

        return $result['result'];
    }

    public function get_ticket_details($id)
    {
        // First, try fetching from transactions
        $transactionSql = "SELECT transactions.*,
                          events.name AS event_name, events.image, events.date_time,
                          tickets.type, tickets.price
                          FROM transactions
                          JOIN events ON transactions.event_id = events.event_id
                          JOIN tickets ON transactions.ticket_id = tickets.ticket_id
                          WHERE transactions.user_id = :user_id";

        $transactionResult = $this->query($transactionSql, ['user_id' => $id], "assoc");

        // Check if transaction data was found without errors
        if (!isset($transactionResult['error']) && !empty($transactionResult['result'])) {
            return $transactionResult['result'];
        }

        // If no transaction found, try ticket_users
        $ticketUserSql = "SELECT ticket_users.*,
                         tickets.type, tickets.price, tickets.event_id AS eventID,
                         events.name AS event_name, events.image, events.date_time
                         FROM ticket_users
                         JOIN tickets ON ticket_users.ticket_id = tickets.ticket_id
                         JOIN events ON tickets.event_id = events.event_id
                         WHERE ticket_users.user_id = :user_id";

        $ticketUserResult = $this->query($ticketUserSql, ['user_id' => $id], "assoc");

        // Return ticket user data if found, otherwise empty array
        return $ticketUserResult['result'] ?? [];
    }

    public function cleanUpOrphanedUserTickets()
    {
        $sql = "DELETE FROM ticket_users WHERE ticket_id NOT IN (SELECT ticket_id FROM tickets)";
        return $this->query($sql);
    }
}