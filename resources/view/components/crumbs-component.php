<?php

declare(strict_types=1);

/**
 * ===================================
 * ==========
 * Component View
 * ==========       ==================
 * ===================================
 */

$user = auth_user();

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= $name ?? 'Dashboard' ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?= URL_ROOT ?>" type="button" class="btn btn-sm btn-outline-info">
                <i class="fa-solid fa-globe mt-1"></i>
            </a>
            <?php if($user && $user['role'] === "admin"): ?>
            <a href="<?= URL_ROOT . "/admin/messages/manage" ?>" type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-envelope"></i>
            </a>
            <a href="<?= URL_ROOT . "/admin/settings/manage" ?>" type="button" class="btn btn-sm btn-outline-danger">
                <svg class="bi mt-1">
                    <use xlink:href="#gear-wide-connected" />
                </svg>
            </a>
            <?php endif; ?>
        </div>
        <a href="<?= URL_ROOT . "/admin/account/{$user['user_id']}" ?>" type="button" class="btn btn-sm btn-outline-tomato d-flex align-items-center gap-1">
            <i class="fa-solid fa-user-secret"></i>
            <span class="fw-bold text-uppercase"><?= $user['first_name'] . " " . $user['last_name'] ?></span>
        </a>
    </div>
</div>