<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** OrganiserTicketController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;
use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;
use celionatti\Bolt\Pagination\Pagination;

use PhpStrike\app\models\Event;
use PhpStrike\app\models\Ticket;
use PhpStrike\app\models\Category;
use PhpStrike\app\models\Payment;


class OrganiserTicketController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("organiser");
        $this->view->setTitle("Manage Tickets");
        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] === "user") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/auth/login");
        }
    }
}