<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminPayoutController
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

class AdminPayoutController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Payouts");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage(Request $request)
    {
        $payment = new Payment();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sql = "SELECT * FROM payments WHERE balance > 0.00";

        $payments = $payment->rawPaginate($sql, [], $page, 5);

        $pagination = new Pagination($payments['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'payments' => $payments['data']['result'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/payouts/manage", $view);
    }

    public function transactions(Request $request)
    {
        $payout = new Payout();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sql = "SELECT * FROM payouts";

        $payouts = $payout->rawPaginate($sql, [], $page, 5);

        $pagination = new Pagination($payouts['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'payouts' => $payouts['data']['result'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/payouts/transactions", $view);
    }

    public function transfer(Request $request, $id)
    {
        $payment = new Payment();
        $fetchData = $payment->findBy(['payment_id' => $id]);

        if (!$fetchData) {
            toast("info", "Payout Not Found!");
            redirect(URL_ROOT . "/admin/payouts/manage");
            return;
        }

        $view = [
            'errors' => getFormMessage(),
            'payment' => $fetchData->toArray(),
            'statusOpts' => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'cancelled' => 'Cancelled'
            ],
        ];

        $this->view->render("admin/payouts/payouts", $view);
    }

    public function send(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }
        $payout = new Payout();
        $payment = new Payment();

        $rules = [
            'to_user' => 'required',
            'account_number' => 'required',
            'amount' => 'required',
            'reference_id' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadData();
        $attributes['payout_id'] = bv_uuid();
        $attributes['user_id'] = $this->currentUser['user_id'];

        if (!$request->validate($rules, $attributes)) {
            redirect(URL_ROOT . "/admin/payouts/manage");
            return; // Ensure the method exits after redirect
        }

        if ($payout->create($attributes)) {
            // Success: Redirect to manage page
            if($payment->deduct_amount($attributes['amount'], $id)) {
                toast("success", "Transfer Data Created Successfully");
                redirect(URL_ROOT . "/admin/payouts/manage");
            }
        } else {
            toast("error", "Transfer Data Failed, Try Again Later!");
            redirect(URL_ROOT . "/admin/payouts/manage");
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return; // Early return for non-GET requests
        }

        $payment = new Payment();
        $fetchData = $payment->findBy(['payment_id' => $id]);

        if (!$fetchData) {
            toast("info", "Payment Info Not Found!");
            redirect(URL_ROOT . "/admin/payouts/manage");
            return;
        }

        $attributes['balance'] = "0.00";

        // Delete the payout
            if ($payment->update($attributes, $id)) {
                toast("success", "Payment Info Deleted!");
            } else {
                toast("error", "Payment delete process failed!");
            }
            redirect(URL_ROOT . "/admin/payouts/manage");
    }
}