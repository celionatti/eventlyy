<?php

declare(strict_types=1);

/**
 * ===================================
 * ==========
 * Component View
 * ==========       ==================
 * ===================================
 */

use PhpStrike\app\components\LogoComponent;

$user = auth_user();

 ?>

 <nav class="navbar navbar-expand-lg fixed-top" aria-label="Eventlyy">
    <div class="container">
      <a class="navbar-brand" href="<?= URL_ROOT ?>">
        <?= renderComponent(LogoComponent::class) ?>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
            <?= renderComponent(LogoComponent::class) ?>
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item my-3">
              <a class="nav-link <?= (active_nav(2, "dashboard")) ? "active" : "" ?>" href="<?= URL_ROOT . "/organiser/dashboard" ?>">Dashboard</a>
            </li>
            <li class="nav-item my-3">
              <a class="nav-link <?= (active_nav(2, "events")) ? "active" : "" ?>" href="<?= URL_ROOT . "/organiser/events/manage" ?>">Events</a>
            </li>
            <li class="nav-item my-3">
              <a class="nav-link <?= (active_nav(2, "discounts")) ? "active" : "" ?> disabled" href="<?= URL_ROOT . "/organiser/discounts/manage" ?>">Discounts</a>
            </li>
          </ul>
          <div class="d-flex gap-2">
            <?php if(is_auth()): ?>
            <div class="dropdown mt-3">
              <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle me-1"></i> <?= $user['first_name'] . " " . $user['last_name'] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= URL_ROOT . "/profile/{$user['user_id']}/account" ?>">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign Out</a></li>
              </ul>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
</nav>