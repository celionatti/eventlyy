<?php

declare(strict_types=1);

/**
 * ===================================
 * ==========
 * Component View
 * ==========       ==================
 * ===================================
 */

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;

 ?>

<div class="col-lg-3">
    <div class="account-nav">
        <div class="text-center mb-4">
            <div class="avatar-upload">
                <img src="<?= get_image("", "avatar") ?>" class="avatar-preview" alt="Profile">
                <button class="btn btn-primary-green btn-sm position-absolute bottom-0 end-0">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            <h4 class="mt-3 text-primary-green text-uppercase"><?= $user["first_name"] . " " . $user["last_name"] ?></h4>
            <small class="text-secondary-green">Member since <?= TimeDateUtils::create($user['created_at'])->toCustomFormat("Y") ?></small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link <?= (active_nav(3, "account")) ? "active" : "" ?>" href="<?= URL_ROOT . "/profile/{$user['user_id']}/account" ?>"><i class="fas fa-user me-2"></i>Profile</a>
            <a class="nav-link <?= (active_nav(3, "tickets")) ? "active" : "" ?>" href="<?= URL_ROOT . "/profile/{$user['user_id']}/tickets" ?>"><i class="fas fa-ticket-alt me-2"></i>My Tickets</a>
            <a class="nav-link disabled" href="#"><i class="fas fa-heart me-2"></i>Saved Events</a>
            <?php if($user['role'] === "admin" || $user['role'] === "organiser"): ?>
            <a class="nav-link <?= (active_nav(3, "payment-details")) ? "active" : "" ?>" href="<?= URL_ROOT . "/profile/{$user['user_id']}/payment-details" ?>"><i class="fas fa-building-columns me-2"></i>Bank Info</a>
            <?php endif; ?>
            <a class="nav-link" href="<?= URL_ROOT . "/profile/{$user['user_id']}/change-password" ?>"><i class="fa-solid fa-lock me-2"></i>Change Password</a>
            <a class="nav-link text-danger" href="<?= URL_ROOT . "/auth/logout" ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </nav>
    </div>
</div>