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
          <li class="nav-item">
            <a class="nav-link <?= (active_nav(1, "events")) ? "active" : "" ?>" href="<?= URL_ROOT . "/events" ?>">Events</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (active_nav(1, "articles")) ? "active" : "" ?>" href="<?= URL_ROOT . "/articles" ?>">Articles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (active_nav(1, "about")) ? "active" : "" ?>" href="<?= URL_ROOT . "/about" ?>">About Us</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Help
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item <?= (active_nav(1, "find-ticket")) ? "active" : "" ?>" href="<?= URL_ROOT . "/find-ticket" ?>">Find Ticket</a></li>
              <li><a class="dropdown-item <?= (active_nav(1, "guide")) ? "active" : "" ?>" href="<?= URL_ROOT . "/guide" ?>">Guide</a></li>
            </ul>
          </li>
        </ul>
        <div class="d-flex gap-2">
          <?php if(is_auth()): ?>
        <div class="dropdown mt-2">
            <button class="btn btn-sm btn-success border-green rounded-pill px-3 py-2 mx-1 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-user-tie p-1"></i>
              <small class="text-uppercase fw-medium"><?= $user['first_name'] . " " . $user['last_name'] ?></small>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item fw-medium" href="<?= URL_ROOT . "/profile/{$user['user_id']}/account" ?>">Profile</a></li>
              <?php if($user['role'] === "admin"): ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-success fw-medium" href="<?= URL_ROOT . "/admin" ?>">Dashboard</a></li>
            <?php elseif($user['role'] === "organiser"): ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-success fw-medium" href="<?= URL_ROOT . "/organiser" ?>">Dashboard</a></li>
            <?php endif; ?>
            </ul>
        </div>

        <a class="text-danger text-decoration-none ms-3 mt-3" href="<?= URL_ROOT . "/auth/logout" ?>" aria-label="Search">
          <i class="fa-solid fa-power-off"></i>
        </a>
      <?php else: ?>
        <a href="<?= URL_ROOT . "/auth/login" ?>" class="btn btn-outline-light px-4 pt-3">Log In</a>
      <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</nav>