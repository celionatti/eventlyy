<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** SiteController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;

use PhpStrike\app\models\Message;
use PhpStrike\app\models\Team;
use PhpStrike\app\models\Sponsor;
use PhpStrike\app\models\Event;

class SiteController extends Controller
{
    public function home(Request $request, Response $reponse)
    {
        $this->view->setTitle("Home");

        $sponsor = new Sponsor();
        $event = new Event();

        $view = [
            'sponsors' => $sponsor->all(),
            'featured' => $event->highlighted_events(),
        ];

        $this->view->render("home", $view);
    }

    public function about(Request $request, Response $reponse)
    {
        $this->view->setTitle("About Us");

        $team = new Team();

        $view = [
            'errors' => getFormMessage(),
            'contact' => retrieveSessionData("contact_data"),
            'teams' => $team->all(),
        ];

        unsetSessionArrayData(['contact_data']);

        $this->view->render("pages/about", $view);
    }

    public function guide(Request $request, Response $reponse)
    {
        $this->view->setTitle("User Guide");

        $view = [

        ];

        $this->view->render("pages/guide", $view);
    }

    public function terms_conditions(Request $request, Response $reponse)
    {
        $this->view->setTitle("Terms and Conditions");

        $view = [

        ];

        $this->view->render("pages/terms-and-conditions", $view);
    }

    public function privacy_policy(Request $request, Response $reponse)
    {
        $this->view->setTitle("Privacy Policy");

        $view = [

        ];

        $this->view->render("pages/privacy-policy", $view);
    }

    public function contact(Request $request)
    {
        $this->view->setTitle("Contact Us");

        $view = [
            'errors' => getFormMessage(),
            'contact' => retrieveSessionData("contact_data"),
        ];

        unsetSessionArrayData(['contact_data']);

        $this->view->render("pages/contact", $view);
    }

    public function send_message(Request $request)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $message = new Message();

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('contact_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/about#message");
            return; // Ensure the method exits after redirect
        }

        $attributes['message_id'] = bv_uuid();

        if ($message->create($attributes)) {
            // Success: Redirect to manage page
            toast("success", "Message Sent!");
            redirect(URL_ROOT . "/about#message");
        } else {
            toast("error", "Message Not Sent!");
            redirect(URL_ROOT . "/about#message");
        }
    }
}