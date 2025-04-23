<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** ProfileController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Helpers\Countries\Countries;
use celionatti\Bolt\Pagination\Pagination;

use PhpStrike\app\models\User;
use PhpStrike\app\models\Payment;
use PhpStrike\app\models\TicketUser;


use celionatti\Bolt\Controller;

class ProfileController extends Controller
{
    public function onConstruct(): void
    {
        $this->setCurrentUser(auth_user());
        if(!$this->currentUser) {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT);
        }
    }

    public function profile(Request $request, $id)
    {
        $this->view->setTitle("Profile");

        $user = new User();

        $fetchData = $user->findBy(['user_id' => $id])->toArray();
        unset($fetchData['password']);
        unset($fetchData['remember_token']);

        if(!$fetchData) {
            toast("error", "User Data not Found!");
            redirect(URL_ROOT);
        }

        $fetchCountries = Countries::getCountries(['NG', 'GH', 'KE']);
        $countriesOptions = [];
        foreach ($fetchCountries as $code => $country) {
            $countriesOptions[$code] = ucfirst($country['name']);
        }

        $view = [
            'errors' => getFormMessage(),
            'countriesOpts' => $countriesOptions,
            'user' => $fetchData ?? retrieveSessionData('user_data'),
        ];

        unsetSessionArrayData(['user_data']);

        $this->view->render("/profile/profile", $view);
    }

    public function update_profile(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $user = new User();

        $fetchData = $user->find($id);

        if (!$fetchData) {
            toast("info", "User Not Found!");
            redirect(URL_ROOT);
        }

        $rules = [
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => "required|email|unique:users.email,user_id != $id",
            'phone' => 'required|numeric',
            'country' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('user_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/profile/{$id}/account");
            return; // Ensure the method exits after redirect
        }

        if ($user->update($attributes, $id)) {
            // Success: Redirect to manage page
            toast("success", "Profile Updated Successfully!");
            redirect(URL_ROOT . "/profile/{$id}/account");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Profile Update Failed!']);
            redirect(URL_ROOT . "/profile/{$id}/account");
        }
    }

    public function profile_payment(Request $request, $id)
    {
        $this->view->setTitle("Payment Details");

        $payment = new Payment();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $paymentData = $payment->paginate($page, 5, ['user_id' => $id]);

        $view = [
            'payments' => $paymentData['data'],
            'errors' => getFormMessage(),
            'payment' => retrieveSessionData('payment_data'),
        ];

        unsetSessionArrayData(['payment_data']);

        $this->view->render("/profile/payment", $view);
    }

    public function insert_payment(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $payment = new Payment();

        $rules = [
            'bank_name' => 'required|min:3',
            'account_number' => 'required|numeric',
            'account_name' => 'required',
            'user_id' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadData();
        $attributes['payment_id'] = bv_uuid();
        $attributes['user_id'] = $this->currentUser['user_id'] ?? null;
        $attributes['balance'] = "0.0";

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('payment_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/profile/{$id}/payment-details");
            return; // Ensure the method exits after redirect
        }

        if ($payment->create($attributes)) {
            // Success: Redirect to manage page
            toast("success", "Bank Payment Added Successfully!");
            redirect(URL_ROOT . "/profile/{$id}/payment-details");
        } else {
            // Failed to create: Redirect to create page
            toast("error", "Adding Bank Payment Failed!");
            redirect(URL_ROOT . "/profile/{$id}/payment-details");
        }
    }

    public function payment_status(Request $request, $id, $status)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $payment = new Payment();

        $data = $payment->find($id);

        if (!$data) {
            toast("info", "Bank Details Not Found!");
            back();
        }
        $attributes = ['status' => $status];

        if ($payment->update($attributes, $id)) {
            // Success: Redirect to manage page
            toast("success", "Bank Status Updated!");
            back();
        } else {
            // Failed to create: Redirect to create page
            toast("error", "Bank Status Failed!");
            back();
        }
    }

    public function tickets(Request $request, $id)
    {
        $this->view->setTitle("User Tickets");

        $ticketUser = new TicketUser();

        $tickets = $ticketUser->tickets_info($id);

        $view = [
            'tickets' => $tickets,
        ];

        $this->view->render("/profile/tickets", $view);
    }

    public function sell(Request $request, $id)
    {
        $this->view->setTitle("Assign Ticket");

        $ticketUser = new TicketUser();

        $ticket = $ticketUser->ticket_details($id);

        // Split the assign to into an array
         $assignTos = isset($ticket['assign_to']) ? explode(',', $ticket['assign_to']) : [];

        $view = [
            'errors' => getFormMessage(),
            'ticket' => $ticket,
            'assign' => $assignTos,
        ];

        $this->view->render("/profile/sell", $view);
    }

    public function update_sell(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $ticketUser = new TicketUser();

        $rules = [
            'assign_to' => 'required',
        ];

        $assignTo = $_POST['assign_to'];

        // Remove empty fields and sanitize inputs
        $filteredNames = array_filter($assignTo, fn($name) => !empty($name));
        $safeNames = array_map('htmlspecialchars', $filteredNames);

        // Convert to comma-separated string
        $nameString = implode(',', $safeNames);

        $attributes['assign_to'] = $nameString;

        if ($ticketUser->update($attributes, $id, "transaction_id") === null) {
            // Success: Redirect to manage page
            toast("success", "Ticket Updated Successfully");
        } else {
            toast("error", "Ticket Updated Failed!");
        }
        back();
    }

    public function change_password(Request $request, $user_id)
    {
        $this->view->setTitle("Change Password");

        $view = [
            'errors' => getFormMessage(),
        ];

        $this->view->render("/profile/change-password", $view);
    }

    public function update_password(Request $request, $user_id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $user = new User();

        $rules = [
            'current_password' => 'required',
            'password' => 'required|min:7|confirmed',
        ];

        // Load and validate data
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/profile/{$user_id}/change-password");
            return;
        }

        $userPassword = $user->verifyCurrentPassword($user_id);
        if($userPassword && password_verify($attributes['current_password'], $userPassword['password'])) {
            $attributes['password'] = password_hash($attributes['password'], PASSWORD_DEFAULT);
            unset($attributes['password_confirm']); // Remove unnecessary data
            unset($attributes['current_password']);

            // Attempt to update the user Password
            if ($user->update($attributes, $userPassword['user_id'])) {
                // On success, redirect to login
                toast("success", "Password Changed Successfully.");
                redirect(URL_ROOT . "/profile/{$user_id}/account");
            }
            toast("error", "Changing of Password failed!");
            redirect(URL_ROOT . "/profile/{$user_id}/account");
            return;
        } else {
            toast("error", "Current Password is not correct!");
            redirect(URL_ROOT . "/profile/{$user_id}/change-password");
        }
    }
}