<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;

use PhpStrike\app\models\Dashboard;
use PhpStrike\app\models\Transaction;
use celionatti\Bolt\Pagination\Pagination;

class AdminController extends Controller
{
    protected $dashboard;

    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Admin Dashboard");
        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] === "user") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/auth/login");
        }
        $this->dashboard = new Dashboard();
    }

    public function dashboard()
    {
        $transaction = new Transaction();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $transactions = $transaction->paginate($page, 2, [], ['created_at' => "DESC"]);

        $pagination = new Pagination($transactions['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'chartData' => $this->getDashboardData(),
            'metrics' => [
                'todaySales' => $this->dashboard->getTodaySales($this->currentUser),
                'successRate' => $this->calculateSuccessRate()
            ],
            'transactions' => $transactions['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/dashboard", $view);
    }

    private function getDashboardData()
    {
        return [
            'ticketPrices' => $this->dashboard->getTicketPriceDistribution($this->currentUser),
            'dailyTransactions' => $this->dashboard->getDailyTransactions($this->currentUser),
            'transactionStatus' => $this->dashboard->getTransactionStatus($this->currentUser),
            'revenueByTicket' => $this->dashboard->getRevenueByTicketType($this->currentUser),
        ];
    }

    private function calculateSuccessRate()
    {
        $statusData = $this->dashboard->getTransactionStatus($this->currentUser);
        $total = array_sum(array_column($statusData, 'count'));
        $completed = array_column($statusData, 'count', 'status')['confirmed'] ?? 0;

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
}