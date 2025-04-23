<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AuthController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use celionatti\Bolt\Authentication\Auth;
use PhpStrike\app\models\User;
use celionatti\Bolt\Helpers\Countries\Countries;
use celionatti\Bolt\Illuminate\Utils\NumberGenerator;

class AuthController extends Controller
{
    protected $auth;

    public function onConstruct(): void
    {
        $this->view->setLayout("auth");
        $this->auth = new Auth();
    }

    public function register_view(Request $request)
    {
        $this->view->setTitle("Registration");

        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        $fetchCountries = Countries::getCountries(['NG']);
        $countriesOptions = [];
        foreach ($fetchCountries as $code => $country) {
            $countriesOptions[$code] = ucfirst($country['name']);
        }

        $view = [
            'errors' => getFormMessage(),
            'auth' => retrieveSessionData("auth_data"),
            'countriesOpts' => $countriesOptions,
        ];

        unsetSessionArrayData(['auth_data']);

        $this->view->render("auth/register", $view);
    }

    public function register(Request $request)
    {
        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        // Ensure the method is POST
        if ("POST" !== $request->getMethod()) {
            setFormMessage(['error' => 'Invalid request method.']);
            redirect(URL_ROOT . "/auth/register");
            return;
        }

        $user = new User();
        $numberGenerator = new NumberGenerator();
        $type = $request->get("type");

        // Validation rules
        $rules = [
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users.email',
            'country' => 'required',
            'terms' => 'required',
            'password' => 'required|min:7|confirmed',
        ];

        if($type === "organization") {
            $rules['business_name'] = "required";
        }

        // Load and validate data
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('auth_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/auth/register?type={$type}");
            return;
        }

        // Prepare user attributes
        $attributes['user_id'] = bv_uuid();
        if($type === "personal") {
            $attributes['business_name'] = null;
        }

        if($type === "organization") {
            $attributes['registration_number'] = $numberGenerator->generateCompanyRegNumber('Evt');
            $attributes['role'] = "organiser";
        }
        $attributes['password'] = password_hash($attributes['password'], PASSWORD_DEFAULT);
        unset($attributes['password_confirm']); // Remove unnecessary data

        // Attempt to create the user
        if ($user->create($attributes)) {
            // On success, redirect to login
            toast("success", "Account Created! Now Login.");
            redirect(URL_ROOT . "/auth/login");
        }
        toast("error", "User creation failed!");
        redirect(URL_ROOT . "/auth/register");
        return;
    }

    public function login(Request $request, Response $reponse)
    {
        $this->view->setTitle("Login");

        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        $view = [
            'errors' => getFormMessage(),
            'auth' => retrieveSessionData("auth_data"),
        ];

        unsetSessionArrayData(['auth_data']);

        $this->view->render("auth/login", $view);
    }

    public function login_access(Request $request)
    {
        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        // Validation rules
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $email = $request->only(['email'])['email'];
        $password = $request->only(['password'])['password'];
        $remember = $request->only(['remember'])['remember'];

        $rememberMe = $remember === "on" ? true : false;

        $auth = $this->auth->login($email, $password, $rememberMe);

        if($auth['success']) {
            toast("success", $auth['message']);
            redirect(URL_ROOT);
        } else {
            toast("error", $auth['message']);
            redirect(URL_ROOT . "/auth/login");
        }
    }

    public function logout(Request $request)
    {
        $this->auth->logout();
        toast("success", "Logout successfully!");
        redirect(URL_ROOT);
    }

    public function redirect_login()
    {
        redirect(URL_ROOT . "/auth/login");
    }

    public function forgot_password(Request $request, Response $reponse)
    {
        $this->view->setTitle("Forgot Password");

        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        $view = [
            'errors' => getFormMessage(),
            'auth' => retrieveSessionData("auth_data"),
        ];

        unsetSessionArrayData(['auth_data']);

        $this->view->render("auth/forgot-password", $view);
    }

    // public function send_reset_token(Request $request)
    // {
    //     if("POST" !== $request->getMethod()) {
    //         return;
    //     }
    //     $user = new User();

    //     $rules = [
    //         'email' => 'required|email',
    //     ];

    //     // Load and validate data
    //     $attributes = $request->loadData();

    //     if (!$request->validate($rules, $attributes)) {
    //         storeSessionData('auth_data', $attributes);
    //         setFormMessage($request->getErrors());
    //         redirect(URL_ROOT . "/auth/forgot-password");
    //         return;
    //     }
    //     if($token = $user->generateResetToken($attributes['email'])) {
    //         // Send email with reset link
    //         $resetLink = URL_ROOT . "/reset-password?token=" . $token;
    //         $to = $attributes['email'];
    //         $subject = "Password Reset Request";
    //         $message = "Click the following link to reset your password: " . $resetLink;
    //         $headers = "From: noreply@eventlyy.com.ng";

    //         mail($to, $subject, $message, $headers);

    //         toast("success", "Password Reset Link Sent, Check your email.");
    //         redirect(URL_ROOT);
    //     }
    // }

    public function send_reset_token(Request $request)
    {
        if ("POST" !== $request->getMethod()) {
            return;
        }

        $user = new User();
        $rules = ['email' => 'required|email'];
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('auth_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/auth/forgot-password");
            return;
        }

        if ($token = $user->generateResetToken($attributes['email'])) {
            try {
                $resetLink = URL_ROOT . "/reset-password?token=" . $token;

                // Get user data (assuming your User model can retrieve it)
                $userData = $user->findBy(['email' => $attributes['email']])->toArray();

                // Initialize MailService
                $mailService = new \celionatti\Bolt\Mailer\MailService();

                // Send email using template
                $mailService->to($attributes['email'])
                    ->subject("Password Reset Request")
                    ->template("/resources/view/pages/reset-password.php", [
                        'user' => [
                            'name' => $userData['name'] ?? 'User',
                            'email' => $attributes['email']
                        ],
                        'reset_url' => $resetLink,
                        'expiration' => 60, // Token expiration in minutes
                        'app_name' => bolt_env('APP_NAME', 'Eventlyy.')
                    ])
                    ->send();

                toast("success", "Password reset link sent! Check your email.");
                redirect(URL_ROOT);
            } catch (\Exception $e) {
                // Log the error
                error_log("Password reset email error: " . $e->getMessage());

                // Store form data and show error
                storeSessionData('auth_data', $attributes);
                toast("error", "Failed to send password reset email. Please try again.");
                redirect(URL_ROOT . "/auth/forgot-password");
            }
        }
    }

    public function reset_password(Request $request)
    {
        $this->view->setTitle("Reset Password");

        $this->setCurrentUser(auth_user());
        if($this->currentUser) {
            redirect(URL_ROOT);
        }

        $token = $request->get("token");

        if(!isset($token)) {
            redirect(URL_ROOT . "/forgot-password");
        }

        $user = new User();

        $users = $user->verifyResetToken($token);

        if(!$users) {
            toast("error", "Invalid Token or Token Expired!");
            redirect(URL_ROOT . "/auth/login");
        }

        $view = [
            'errors' => getFormMessage(),
            'auth' => retrieveSessionData("auth_data"),
        ];

        unsetSessionArrayData(['auth_data']);

        $this->view->render("auth/reset-password", $view);
    }

    public function update_password(Request $request)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $token = $request->get("token");

        if(!isset($token)) {
            redirect(URL_ROOT . "/forgot-password");
        }

        $user = new User();

        $users = $user->verifyResetToken($token);

        if(!$users) {
            toast("error", "Invalid Token or Token Expired!");
            redirect(URL_ROOT . "/auth/login");
        }

        $rules = [
            'password' => 'required|min:7|confirmed',
        ];

        // Load and validate data
        $attributes = $request->loadData();

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('auth_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/auth/reset-password?token={$request->get("token")}");
            return;
        }
        $attributes['password'] = password_hash($attributes['password'], PASSWORD_DEFAULT);
        unset($attributes['password_confirm']); // Remove unnecessary data

        // Attempt to update the user
        if ($user->update($attributes, $users['user_id'])) {
            // On success, redirect to login
            toast("success", "Password Updated! Now Login.");
            redirect(URL_ROOT . "/auth/login");
        }
        toast("error", "Password Update failed!");
        redirect(URL_ROOT . "/auth/reset-password");
        return;
    }
}