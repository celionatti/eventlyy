<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Dashboard Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Dashboard extends Model
{
    /**
     * Get ticket price distribution based on user role
     * @param array $userId Array containing user_id and role
     * @return array
     */
    public function getTicketPriceDistribution($userId)
    {
        $baseQuery = "SELECT t.type, t.price, COUNT(*) as count
                      FROM tickets t";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see tickets from their events
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.event_id
                            WHERE e.user_id = :user_id";

            $params = ['user_id' => $userId['user_id']];
        } else {
            // Admin users can see all tickets
            $params = [];
        }

        $baseQuery .= " GROUP BY t.type, t.price
                        ORDER BY t.price DESC";

        return $this->query($baseQuery, $params, "assoc")['result'];
    }

    public function getDailyTransactions($userId)
    {
        $baseQuery = "SELECT DATE(t.created_at) as date,
                         COUNT(*) as transaction_count,
                         SUM(t.total_amount) as daily_total
                  FROM transactions t";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see their own transactions
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.id
                            WHERE e.user_id = :user_id
                            AND t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

            $params = ['user_id' => $userId['user_id']];
        } else {
            // Admin users can see all transactions
            $baseQuery .= " WHERE t.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $params = [];
        }

        $baseQuery .= " GROUP BY DATE(t.created_at)
                        ORDER BY date";

        return $this->query($baseQuery, $params, "assoc")['result'];
    }

    /**
     * Get transaction status summary based on user role
     * @param array $userId Array containing user_id and role
     * @return array
     */
    public function getTransactionStatus($userId)
    {
        $baseQuery = "SELECT t.status, COUNT(*) as count, SUM(t.amount) as amount
                      FROM transactions t";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see transactions from their events
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.event_id
                            WHERE e.user_id = :user_id";

            $params = ['user_id' => $userId['user_id']];
        } else {
            // Admin users can see all transactions
            $params = [];
        }

        $baseQuery .= " GROUP BY t.status";

        return $this->query($baseQuery, $params, "assoc")['result'];
    }

    public function getTotalTransactionStatus($userId)
    {
        $baseQuery = "SELECT t.status, COUNT(*) as count, SUM(t.total_amount) as total_amount
                      FROM transactions t";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see transactions from their events
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.event_id
                            WHERE e.user_id = :user_id";

            $params = ['user_id' => $userId['user_id']];
        } else {
            // Admin users can see all transactions
            $params = [];
        }

        $baseQuery .= " GROUP BY t.status";

        return $this->query($baseQuery, $params, "assoc")['result'];
    }

    /**
     * Get revenue by ticket type based on user role
     * @param array $userId Array containing user_id and role
     * @return array
     */
    public function getRevenueByTicketType($userId)
    {
        $baseQuery = "SELECT t.type,
                             COUNT(tr.id) as sales_count,
                             SUM(tr.amount) as total_revenue
                      FROM tickets t
                      JOIN transactions tr ON t.ticket_id = tr.ticket_id";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see revenue from their events
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.event_id
                            WHERE e.user_id = :user_id
                            AND tr.status = 'confirmed'";

            $params = ['user_id' => $userId['user_id']];
        } else {
            // Admin users can see all revenue
            $baseQuery .= " WHERE tr.status = 'confirmed'";
            $params = [];
        }

        $baseQuery .= " GROUP BY t.type
                        ORDER BY total_revenue DESC";

        return $this->query($baseQuery, $params, "assoc")['result'];
    }

    public function getTodaySales($userId)
    {
        $baseQuery = "SELECT SUM(t.amount) as total
                      FROM transactions t";

        if ($userId['role'] !== "admin") {
            // Non-admin users can only see sales from their events
            $baseQuery .= " INNER JOIN events e ON t.event_id = e.event_id
                            WHERE e.user_id = :user_id
                            AND DATE(t.created_at) = CURDATE()
                            AND t.status = :status";

            $params = ['user_id' => $userId['user_id'], 'status' => 'confirmed'];
        } else {
            // Admin users can see all sales
            $baseQuery .= " WHERE DATE(t.created_at) = CURDATE()
                            AND t.status = :status";
            $params = ['status' => 'confirmed'];
        }

        return $this->query($baseQuery, $params, "assoc")['result'];
    }
}