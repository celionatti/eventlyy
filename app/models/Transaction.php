<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Transaction Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Transaction extends Model
{
    protected $primaryKey = "transaction_id";
    protected $fillable = ['transaction_id', 'user_id', 'first_name', 'last_name', 'email', 'phone', 'event_id', 'ticket_id', 'quantity', 'amount', 'total_amount', 'reference_id', 'token', 'status'];

    public function transaction_details($id)
    {
        $sql = "SELECT transactions.*,
                events.name AS event_name, events.image, events.date_time, events.location,
                tickets.type, tickets.price
                FROM transactions
                JOIN events ON transactions.event_id = events.event_id
                JOIN tickets ON transactions.ticket_id = tickets.ticket_id
                WHERE transactions.transaction_id = :transaction_id";

        // Assuming the query method properly handles binding and returns an associative array
        $result = $this->query($sql, ['transaction_id' => $id], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'][0];
    }

    public function transactions_details($id)
    {
        $sql = "SELECT transactions.quantity, transactions.amount, transactions.token, transactions.status, transactions.first_name, transactions.last_name, transactions.phone, transactions.email,
            events.name AS event_name,
            tickets.type, tickets.price
            FROM transactions
            JOIN events ON transactions.event_id = events.event_id
            JOIN tickets ON transactions.ticket_id = tickets.ticket_id
            WHERE transactions.event_id = :event_id AND transactions.status = :status";

        // Assuming the query method properly handles binding and returns an associative array
        $result = $this->query($sql, ['event_id' => $id, 'status' => 'confirmed'], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'];
    }

    public function event_total($id)
    {
        $sql = "SELECT SUM(amount) AS total_amount
             FROM transactions
             WHERE event_id = :event_id AND status = :status";

        $result = $this->query($sql, ['event_id' => $id, 'status' => 'confirmed'], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'][0];
    }

    public function ticket_details($id)
    {
        $sql = "SELECT t.transaction_id AS transactionId, t.user_id, t.first_name, t.last_name, t.email, t.phone, t.created_at AS purchase_date, t.amount, tk.type AS ticket_type, tu.token AS ticket_token, tu.status AS ticket_status FROM transactions t JOIN ticket_users tu ON t.transaction_id = tu.transaction_id JOIN tickets tk ON t.ticket_id = tk.ticket_id WHERE t.status = :status AND t.transaction_id = :transaction_id";

        $result = $this->query($sql, ['transaction_id' => $id, 'status' => 'confirmed'], "assoc");

        // Check for errors or empty results
        if (isset($result['error']) || empty($result['result'])) {
            return []; // Or handle error appropriately
        }

        return $result['result'][0];
    }

    public function cleanUpOrphanedTransactions()
    {
        $sql = "DELETE FROM transactions WHERE event_id NOT IN (SELECT event_id FROM events)";
        return $this->query($sql);
    }

    public function cleanUpPendingTransactions()
    {
        $sql = "DELETE FROM transactions WHERE status = 'pending'";
        return $this->query($sql);
    }
}