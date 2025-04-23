<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminTransactionController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;
use celionatti\Bolt\Pagination\Pagination;

use PhpStrike\app\models\Payout;
use PhpStrike\app\models\Payment;
use PhpStrike\app\models\Transaction;

class AdminTransactionController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Transactions");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] === "user") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function organiser_manage(Request $request)
    {
        $transaction = new Transaction();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if(auth_role($this->currentUser['role'], ['admin'])) {
            $sql = "SELECT transactions.*,
                   events.name AS event_name,
                   events.image,
                   events.date_time,
                   events.location,
                   tickets.type,
                   tickets.price,
                   (SELECT SUM(amount)
                    FROM transactions
                    WHERE status = 'confirmed') as confirmed_total
            FROM transactions
            JOIN events ON transactions.event_id = events.event_id
            JOIN tickets ON transactions.ticket_id = tickets.ticket_id";

        $transactions = $transaction->rawPaginate($sql, [], $page, 5);
        } else {
            $sql = "SELECT transactions.*,
                   events.name AS event_name,
                   events.image,
                   events.date_time,
                   events.location,
                   tickets.type,
                   tickets.price,
                   (SELECT SUM(amount)
                    FROM transactions t2
                    WHERE t2.event_id = events.event_id
                    AND t2.status = 'confirmed') as confirmed_total
            FROM transactions
            JOIN events ON transactions.event_id = events.event_id
            JOIN tickets ON transactions.ticket_id = tickets.ticket_id
            WHERE events.user_id = :user_id";

        $transactions = $transaction->rawPaginate($sql, ['user_id' => $this->currentUser['user_id']], $page, 5);
        }

        $pagination = new Pagination($transactions['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'transactions' => $transactions['data']['result'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/transactions/manage-organisers", $view);
    }
}