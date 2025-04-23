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

<div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
    <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">Eventlyy.</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2 active" aria-current="page" href="<?= URL_ROOT . "/admin/dashboard" ?>">
                        <i class="bi fa-solid fa-house"></i>
                        Dashboard
                    </a>
                </li>
                <?php if($user && $user['role'] === "admin"): ?>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/articles/manage" ?>">
                        <i class="bi fa-regular fa-newspaper"></i>
                        Articles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/categories/manage" ?>">
                        <i class="bi fa-solid fa-tags"></i>
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/users/manage" ?>">
                        <i class="bi fa-solid fa-users"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/payouts/manage" ?>">
                        <i class="bi fa-solid fa-file-invoice"></i>
                        Payouts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/sponsors/manage" ?>">
                        <i class="bi fa-solid fa-list"></i>
                        Sponsors
                    </a>
                </li>
                <?php endif; ?>
                <?php if($user && $user['role'] === "organiser" || $user['role'] === "admin"): ?>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/organiser/transactions/manage" ?>">
                        <i class="bi fa-solid fa-file-invoice"></i>
                        Transactions
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-body-secondary text-uppercase">
                <span>Event Tickets</span>
                <a class="link-secondary" href="<?= URL_ROOT . "/admin/tickets/create" ?>" aria-label="Manage Event Tickets">
                    <i class="bi fa-solid fa-plus"></i>
                </a>
            </h6>
            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/events/manage" ?>">
                        <i class="bi fa-solid fa-list"></i>
                        Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="<?= URL_ROOT . "/admin/tickets/manage" ?>">
                        <i class="bi fa-solid fa-calendar"></i>
                        Tickets
                    </a>
                </li>
            </ul>

            <hr class="my-3">

            <ul class="nav flex-column mb-auto">
                <?php if($user && $user['role'] === "admin"): ?>
                <!-- admin menu -->
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-danger d-flex align-items-center gap-2" href="<?= URL_ROOT . "/auth/logout" ?>">
                        <i class="fa-solid fa-power-off"></i>
                        Sign out
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>