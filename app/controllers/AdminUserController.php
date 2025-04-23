<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminUserController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\User;

class AdminUserController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Users");
        $this->setCurrentUser(auth_user());

        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage()
    {
        $user = new User();

        $view = [
            'users' => $user::all()
        ];

        $this->view->render("admin/users/manage", $view);
    }

    public function create(Request $request)
    {
        $view = [
            'errors' => getFormMessage(),
            'user' => retrieveSessionData('user_data'),
            'roleOpts' => [
                'user' => 'User',
                'admin' => 'Admin',
                'organiser' => 'Event Organiser'
            ],
        ];

        unsetSessionArrayData(['user_data']);

        $this->view->render("admin/users/create", $view);
    }

    public function insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create user if validation passes
            $user = new User();

            $rules = [
                'first_name' => 'required|string|min:3|max:50',
                'last_name' => 'required|string|min:3|max:50',
                'email' => 'required|email|unique:users.email',
                'phone' => 'required|numeric',
                'role' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ];

            // Load and validate data
            $attributes = $request->loadData();

            if (!$request->validate($rules, $attributes)) {
                storeSessionData('user_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/users/create");
                return; // Ensure the method exits after redirect
            }

            $attributes['user_id'] = bv_uuid();
            $hashedPassword = password_hash($attributes['password'], PASSWORD_DEFAULT);
            $attributes['password'] = $hashedPassword;
            unset($attributes['password_confirm']);

            if ($user->create($attributes)) {
                // Success: Redirect to manage page
                toast("success", "User Created Successfully");
                redirect(URL_ROOT . "/admin/users/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'User creation failed!']);
                redirect(URL_ROOT . "/admin/users/manage");
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $user = new User();

        $fetchData = $user->find($id)->toArray();
        unset($fetchData['password']);

        if(!$fetchData) {
            toast("error", "Not available het ");
            return;
        }

        $view = [
            'errors' => getFormMessage(),
            'user' => $fetchData ?? retrieveSessionData('user_data'),
            'roleOpts' => [
                'user' => 'User',
                'admin' => 'Admin',
                'organiser' => 'Event Organiser'
            ],
        ];

        unsetSessionArrayData(['user_data']);

        $this->view->render("admin/users/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create category if validation passes
            $user = new User();

            $fetchData = $user->find($id);

            if (!$fetchData) {
                toast("info", "User Not Found!");
                redirect(URL_ROOT . "/admin/users/manage");
            }

            $rules = [
                'first_name' => 'required|string|min:3|max:50',
                'last_name' => 'required|string|min:3|max:50',
                'email' => "required|email|unique:users.email,user_id != $id",
                'phone' => 'required|numeric',
                'role' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadData();

            if (!$request->validate($rules, $attributes)) {
                storeSessionData('user_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/users/edit/{$id}");
                return; // Ensure the method exits after redirect
            }

            if ($user->update($attributes, $id)) {
                // Success: Redirect to manage page
                toast("success", "User Updated Successfully");
                redirect(URL_ROOT . "/admin/users/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'User Update Process failed!']);
                redirect(URL_ROOT . "/admin/users/manage");
            }
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            // Proceed to create user if validation passes
            $user = new User();

            $fetchData = $user->find($id);

            if (!$fetchData) {
                toast("info", "User Not Found!");
                redirect(URL_ROOT . "admin/users/manage");
            }
            if($user->delete($id)) {
                toast("success", "User Deleted Successfully");
                redirect(URL_ROOT . "/admin/users/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'User delete process failed!']);
                redirect(URL_ROOT . "/admin/users/manage");
            }
        }
    }
}