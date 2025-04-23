<?php

declare(strict_types=1);

use celionatti\Bolt\Bolt;
use PhpStrike\app\controllers\AuthController;
use PhpStrike\app\controllers\SiteController;
use PhpStrike\app\controllers\EventController;
use PhpStrike\app\controllers\ArticleController;
use PhpStrike\app\controllers\TicketController;
use PhpStrike\app\controllers\FreeTicketController;
use PhpStrike\app\controllers\FindTicketController;
use PhpStrike\app\controllers\PaystackController;
use PhpStrike\app\controllers\FlutterwaveController;
use PhpStrike\app\controllers\ProfileController;

use PhpStrike\app\controllers\OrganiserController;
use PhpStrike\app\controllers\OrganiserEventController;

use PhpStrike\app\controllers\AdminController;
use PhpStrike\app\controllers\AdminArticleController;
use PhpStrike\app\controllers\AdminTicketController;
use PhpStrike\app\controllers\AdminUserController;
use PhpStrike\app\controllers\AdminEventController;
use PhpStrike\app\controllers\AdminCategoryController;
use PhpStrike\app\controllers\AdminSettingController;
use PhpStrike\app\controllers\AdminPayoutController;
use PhpStrike\app\controllers\AdminSponsorController;
use PhpStrike\app\controllers\AdminMessageController;
use PhpStrike\app\controllers\AdminTransactionController;

/** @var Bolt $bolt */

/**
 * ========================================
 * Bolt - Router Usage ====================
 * ========================================
 */

// $bolt->router->get("/user", function() {
//     echo "User function routing...";
// });

$bolt->router->group(['prefix' => '/', []], function($router) {
    $router->get('/', [SiteController::class, 'home']);
    $router->get('login', [AuthController::class, 'redirect_login']);
    $router->get('/events', [EventController::class, 'events']);
    $router->get('/events/search', [EventController::class, 'events_search']);
    $router->get('/events/categories/{:category_id}', [EventController::class, 'categories']);
    $router->get('/events/view/{:id}', [EventController::class, 'event']);
    $router->get('/articles', [ArticleController::class, 'articles']);
    $router->get('/articles/search', [ArticleController::class, 'search']);
    $router->get('/articles/view/{:id}', [ArticleController::class, 'article']);
    $router->get('/guide', [SiteController::class, 'guide']);
    $router->get('/about', [SiteController::class, 'about']);
    $router->get('/contact', [SiteController::class, 'contact']);
    $router->post('/send-message', [SiteController::class, 'send_message']);
    $router->get('/find-ticket', [FindTicketController::class, 'find_ticket']);
    $router->post('/find-ticket', [FindTicketController::class, 'find']);
    $router->get('/find-ticket/{:id}', [FindTicketController::class, 'ticket_details']);

    $router->get('/terms-and-conditions', [SiteController::class, 'terms_conditions']);
    $router->get('/privacy-policy', [SiteController::class, 'privacy_policy']);

    $router->get('/events/tickets/{:event_id}/contact', [TicketController::class, 'contact']);
    $router->post('/events/tickets/{:event_id}/contact', [TicketController::class, 'insert_contact']);

    $router->get('/events/tickets/{:event_id}/payment/{:payment_id}', [TicketController::class, 'payment']);

    // Free Ticket
    $router->get('/events/tickets/{:event_id}/free', [FreeTicketController::class, 'free']);
    $router->post('/events/tickets/{:event_id}/free', [FreeTicketController::class, 'insert_free']);

    $router->get('/ticket-details/{:id}', [FreeTicketController::class, 'details']);
    $router->get('/free-tickets/download/{:id}', [FreeTicketController::class, 'downloadpdf']);

    // Paystack Payment
    $router->post('/initialize-payment', [PaystackController::class, 'initialize']);
    $router->get('/verify-payment/{:id}', [PaystackController::class, 'verifyPayment']);
    $router->get('/payment-success/{:id}', [PaystackController::class, 'success']);
    $router->get('/tickets/download/{:id}', [PaystackController::class, 'downloadpdf']);

    // Flutterwave Payment
    $router->post('/initialize-flutterwave-payment', [FlutterwaveController::class, 'initialize']);
    $router->get('/flutterwave-verify-payment/{:id}', [FlutterwaveController::class, 'handleCallback']);

    // Profile Sell Ticket
    // $router->get('/tickets/sell/{:id}', [ProfileController::class, 'sell']);
    // $router->post('/tickets/sell/{:id}', [ProfileController::class, 'update_sell']);
});

/** Profile Routes */
$bolt->router->group(['prefix' => '/profile', []], function($router) {
    $router->get('/{:id}/account', [ProfileController::class, 'profile'])->name('profile');
    $router->post('/{:id}/account', [ProfileController::class, 'update_profile']);
    $router->get('/{:id}/payment-details', [ProfileController::class, 'profile_payment'])->name('profile-payment');
    $router->get('/{:id}/payment-details/new', [ProfileController::class, 'new_payment'])->name('profile-payment');
    $router->post('/{:id}/payment-details/new', [ProfileController::class, 'insert_payment']);
    $router->post('/{:id}/payment-details/status/{:status}', [ProfileController::class, 'payment_status']);

    $router->get('/{:id}/tickets', [ProfileController::class, 'tickets']);
    $router->get('/{:user_id}/tickets/sell/{:id}', [ProfileController::class, 'sell']);
    $router->post('/{:user_id}/tickets/sell/{:id}', [ProfileController::class, 'update_sell']);

    $router->get('/{:user_id}/change-password', [ProfileController::class, 'change_password']);
    $router->post('/{:user_id}/change-password', [ProfileController::class, 'update_password']);
});

/** Auth Routes */
$bolt->router->group(['prefix' => '/auth', []], function($router) {
    $router->get('/login', [AuthController::class, 'login']);
    $router->post('/login', [AuthController::class, 'login_access']);
    $router->get('/register', [AuthController::class, 'register_view']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->get('/logout', [AuthController::class, 'logout']);

    $router->get('/forgot-password', [AuthController::class, 'forgot_password']);
    $router->post('/forgot-password', [AuthController::class, 'send_reset_token']);
    $router->get('/reset-password', [AuthController::class, 'reset_password']);
    $router->post('/reset-password', [AuthController::class, 'update_password']);
});

/** Organiser Routes */
$bolt->router->get("/organiser", [OrganiserController::class, "dashboard"]);
$bolt->router->group(['prefix' => '/organiser', []], function($router) {
    $router->get('/dashboard', [OrganiserController::class, 'dashboard']);
});

/** Organiser Events Routes */
$bolt->router->group(['prefix' => '/organiser/events', []], function($router) {
    $router->get('/manage', [OrganiserEventController::class, 'manage']);
    $router->get('/details/{:id}', [OrganiserEventController::class, 'details']);
    $router->get('/create', [OrganiserEventController::class, 'create']);
    $router->post('/create', [OrganiserEventController::class, 'insert']);
    $router->get('/create/ticket/{:id}', [OrganiserEventController::class, 'ticket']);
    $router->post('/create/ticket/{:id}', [OrganiserEventController::class, 'insert_ticket']);
    $router->get('/create/preview/{:id}', [OrganiserEventController::class, 'preview']);

    $router->get('/edit/{:id}', [OrganiserEventController::class, 'edit']);
    $router->post('/edit/{:id}', [OrganiserEventController::class, 'update']);
    $router->post('/delete/{:id}', [OrganiserEventController::class, 'delete']);
    $router->get('/details/{:id}/print', [OrganiserEventController::class, 'generatepdf']);
});

/** Admin Routes */
$bolt->router->get("/admin", [AdminController::class, "dashboard"]);
$bolt->router->group(['prefix' => '/admin', []], function($router) {
    $router->get('/dashboard', [AdminController::class, 'dashboard']);
});

/** Admin Users Routes */
$bolt->router->group(['prefix' => '/admin/users', []], function($router) {
    $router->get('/manage', [AdminUserController::class, 'manage']);
    $router->get('/create', [AdminUserController::class, 'create']);
    $router->post('/create', [AdminUserController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminUserController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminUserController::class, 'update']);
    $router->post('/delete/{:id}', [AdminUserController::class, 'delete']);
});

/** Admin Events Routes */
$bolt->router->group(['prefix' => '/admin/events', []], function($router) {
    $router->get('/manage', [AdminEventController::class, 'manage']);
    $router->get('/create', [AdminEventController::class, 'create']);
    $router->post('/create', [AdminEventController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminEventController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminEventController::class, 'update']);
    $router->post('/delete/{:id}', [AdminEventController::class, 'delete']);
    $router->get('/details/{:id}', [AdminEventController::class, 'details']);
    $router->get('/details/{:id}/print', [AdminEventController::class, 'generatepdf']);
});

/** Admin Tickets Routes */
$bolt->router->group(['prefix' => '/admin/tickets', []], function($router) {
    $router->get('/manage', [AdminTicketController::class, 'manage']);
    $router->get('/create', [AdminTicketController::class, 'create']);
    $router->post('/create', [AdminTicketController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminTicketController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminTicketController::class, 'update']);
    $router->post('/delete/{:id}', [AdminTicketController::class, 'delete']);
});

/** Admin Articles Routes */
$bolt->router->group(['prefix' => '/admin/articles', []], function($router) {
    $router->get('/manage', [AdminArticleController::class, 'manage']);
    $router->get('/create', [AdminArticleController::class, 'create']);
    $router->post('/create', [AdminArticleController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminArticleController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminArticleController::class, 'update']);
    $router->post('/delete/{:id}', [AdminArticleController::class, 'delete']);
});

/** Admin Category Routes */
$bolt->router->group(['prefix' => '/admin/categories', []], function($router) {
    $router->get('/manage', [AdminCategoryController::class, 'manage']);
    $router->get('/create', [AdminCategoryController::class, 'create']);
    $router->post('/create', [AdminCategoryController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminCategoryController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminCategoryController::class, 'update']);
    $router->post('/delete/{:id}', [AdminCategoryController::class, 'delete']);
});

/** Admin Settings Routes */
$bolt->router->group(['prefix' => '/admin/settings', []], function($router) {
    $router->get('/manage', [AdminSettingController::class, 'manage']);
    $router->get('/create', [AdminSettingController::class, 'create']);
    $router->post('/create', [AdminSettingController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminSettingController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminSettingController::class, 'update']);
    $router->post('/delete/{:id}', [AdminSettingController::class, 'delete']);

    $router->get('/teams', [AdminSettingController::class, 'teams']);
    $router->get('/teams/create', [AdminSettingController::class, 'team_create']);
    $router->post('/teams/create', [AdminSettingController::class, 'team_insert']);
    $router->get('/teams/edit/{:id}', [AdminSettingController::class, 'team_edit']);
    $router->post('/teams/edit/{:id}', [AdminSettingController::class, 'team_update']);
    $router->post('/teams/delete/{:id}', [AdminSettingController::class, 'team_delete']);
});

/** Admin Payouts Routes */
$bolt->router->group(['prefix' => '/admin/payouts', []], function($router) {
    $router->get('/manage', [AdminPayoutController::class, 'manage']);
    $router->get('/transactions', [AdminPayoutController::class, 'transactions']);
    $router->get('/transfer/{:id}', [AdminPayoutController::class, 'transfer']);
    $router->post('/transfer/{:id}', [AdminPayoutController::class, 'send']);
    $router->post('/delete/{:id}', [AdminPayoutController::class, 'delete']);
});

/** Admin Sponsors Routes */
$bolt->router->group(['prefix' => '/admin/sponsors', []], function($router) {
    $router->get('/manage', [AdminSponsorController::class, 'manage']);
    $router->get('/create', [AdminSponsorController::class, 'create']);
    $router->post('/create', [AdminSponsorController::class, 'insert']);
    $router->get('/edit/{:id}', [AdminSponsorController::class, 'edit']);
    $router->post('/edit/{:id}', [AdminSponsorController::class, 'update']);
    $router->post('/delete/{:id}', [AdminSponsorController::class, 'delete']);
});

/** Admin Messages Routes */
$bolt->router->group(['prefix' => '/admin/messages', []], function($router) {
    $router->get('/manage', [AdminMessageController::class, 'manage']);
    $router->post('/delete/{:id}', [AdminMessageController::class, 'delete']);
});

/** Organiser Routes */
$bolt->router->group(['prefix' => '/organiser', []], function($router) {
    $router->get('/transactions/manage', [AdminTransactionController::class, 'organiser_manage']);
});
