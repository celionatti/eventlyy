<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminMessageController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Controller;
use celionatti\Bolt\Http\Request;

use PhpStrike\app\models\Message;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Pagination\Pagination;

class AdminMessageController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Messages");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage()
    {
        $message = new Message();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $messages = $message->paginate($page, 5);

        $pagination = new Pagination($messages['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'messages' => $messages['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/messages/manage", $view);
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            // Proceed to create message if validation passes
            $message = new Message();

            $fetchData = $message->find($id);

            if (!$fetchData) {
                toast("info", "Message Not Found!");
                redirect(URL_ROOT . "admin/messages/manage");
            }
            if($message->delete($id)) {
                toast("success", "Message Deleted Successfully");
                redirect(URL_ROOT . "/admin/messages/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Message delete process failed!']);
                redirect(URL_ROOT . "/admin/messages/manage");
            }
        }
    }
}
