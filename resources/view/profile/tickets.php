<?php

/**
 * Framework Title: PhpStrike Framework
 * Creator: Celio natti
 * version: 1.0.0
 * Year: 2023
 *
 *
 * This view page start name{style,script,content}
 * can be edited, base on what they are called in the layout view
 */

use PhpStrike\app\components\NavComponent;
use PhpStrike\app\components\FooterComponent;
use PhpStrike\app\components\ProfileSidebarComponent;

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;

$user = auth_user();

?>

<?php $this->start('header') ?>
<style type="text/css">
    .account-nav {
        background: var(--dark-blue);
        color: #fff !important;
        border-radius: 15px;
        padding: 1rem;
    }

    .account-nav .nav-link {
        color: var(--primary-green);
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
    }

    .account-nav .nav-link.active {
        background: var(--primary-green);
        color: white !important;
    }

    .ticket-card {
        border: 2px solid var(--light-green);
        transition: all 0.3s ease;
    }

    .ticket-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-green);
    }

    .ticket-qr {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .order-timeline {
        border-left: 3px solid var(--light-green);
        margin-left: 1rem;
        padding-left: 2rem;
    }

    .avatar-upload {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-green);
    }

    .status-badge {
        background: var(--secondary-green);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9em;
    }

    label {
        color: var(--secondary-green);
    }
</style>
<?php $this->end() ?>

<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<!-- Profile Section -->
<section class="py-5 mt-3">
    <div class="container">
        <div class="row g-3">
            <!-- Side Navigation -->
            <?= renderComponent(ProfileSidebarComponent::class, ['user' => $user]); ?>

            <!-- Profile Content -->
            <div class="col-lg-9">
                <div class="bg-white rounded-3 p-4">
                    <h2 class="text-primary-green mb-4">Purchased Tickets</h2>

                    <!-- Purchased Tickets -->
                    <div class="mb-5">
                        <?php if($tickets): ?>
                        <!-- Ticket Cards -->
                        <div class="row g-3">
                        <?php foreach($tickets as $ticket): ?>
                            <?php
                            $transactionId = $ticket['transaction_id'] ?? null;
                            $ticketType = strtolower(trim($ticket['type'] ?? ''));
                            $isFreeTicket = ($ticketType === 'free');

                            if ($transactionId && is_string($transactionId)) {
                                $sanitizedId = htmlspecialchars($transactionId, ENT_QUOTES, 'UTF-8');

                                // Build the base URL safely
                                $baseUrl = $isFreeTicket
                                    ? URL_ROOT . "/free-tickets/download/"
                                    : URL_ROOT . "/tickets/download/";

                                $downloadUrl = $baseUrl . $sanitizedId;
                            } else {
                                // Fallback or error handling
                                $downloadUrl = 'javascript:void(0);';
                                toast("error", "Missing transaction ID");
                            }
                             ?>
                            <div class="col-12">
                                <div class="ticket-card bg-light-green p-4 rounded-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="ticket-qr">
                                                <i class="fas fa-qrcode fa-3x text-secondary-green"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary-green"><?= $ticket['event_name'] ?></h5>
                                            <div class="order-timeline">
                                                <div class="mb-2">
                                                    <i class="fas fa-calendar me-2 text-secondary-green"></i>
                                                    <span class="text-secondary-green"><?= TimeDateUtils::create($ticket['date_time'])->toCustomFormat('F j, Y | h:i A') ?></span>
                                                </div>
                                                <div class="mb-2">
                                                    <i class="fas fa-map-marker-alt me-2 text-secondary-green"></i>
                                                    <span class="text-secondary-green"><?= $ticket['location'] ?></span>
                                                </div>
                                                <span class="status-badge bg-primary-green"><?= $ticket['quantity'] . " " . $ticket['type'] ?><i class="fas fa-ticket-alt ms-1"></i></span>
                                                <span class="fw-bold"> . </span>
                                                <span class="status-badge bg-primary-green"><?= (strtotime(date('Y-m-d')) < strtotime($ticket['date_time'])) ? 'Upcoming' : 'Completed'; ?>
</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end my-1">
                                            <a href="<?= $downloadUrl ?>" class="btn btn-dark btn-sm px-3 mb-2">
                                                <i class="fas fa-download me-2"></i>Ticket
                                            </a>
                                            <div class="text-secondary-green small">Order #: <?= $ticket['token'] ?></div>
                                            <a href="<?= URL_ROOT . "/profile/{$user['user_id']}/tickets/sell/{$ticket['transaction_id']}" ?>" class="btn btn-danger mb-2">
                                                <i class="fas fa-exchange me-2"></i>Sell
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                            <!-- Add more ticket cards -->
                        </div>
                        <?php else: ?>
                            <h4 class="text-center text-primary-green">No Tickets Purchased Yet!</h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>