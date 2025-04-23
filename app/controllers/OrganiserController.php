<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** OrganiserController
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

class OrganiserController extends Controller
{
    protected $dashboard;

    public function onConstruct(): void
    {
        $this->view->setLayout("organiser");
        $this->view->setTitle("Organiser Dashboard");
        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] === "user") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/auth/login");
        }
        $this->dashboard = new Dashboard();
    }

    public function dashboard()
    {
        $view = [

        ];

        $this->view->render("organiser/dashboard", $view);
    }
}