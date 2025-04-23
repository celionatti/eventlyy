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
use PhpStrike\app\components\LogoComponent;

use celionatti\Bolt\Forms\BootstrapForm;

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>

<?php $this->start('header') ?>
<style type="text/css">
  .success-icon {
      width: 100px;
      height: 100px;
      background: var(--primary-green);
      border-radius: 50%;
      animation: bounce 2s;
  }

  @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
  }

  .ticket-card {
      border: 2px solid var(--dark-blue);
      transition: all 0.3s ease;
  }
</style>
<?php $this->end() ?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<main class="py-5 mt-3">
  <div class="container text-center">
      <!-- Success Header -->
      <div class="success-icon d-inline-flex align-items-center justify-content-center mb-4">
          <i class="fas fa-check fa-3x text-white"></i>
      </div>
      <h1 class="text-primary-green mb-3">Payment Successful!</h1>
      <p class="lead text-secondary-green mb-5">Your tickets are confirmed. Details have been sent to <?= $transaction['email'] ?></p>

      <!-- Transaction Details -->
      <div class="row justify-content-center mb-5">
          <div class="col-lg-6 col-12">
              <div class="bg-white p-4 rounded-3">
                  <div class="row text-start">
                      <div class="col-md-6 col-12">
                          <p class="text-secondary-green">Amount Paid:</p>
                          <p class="text-secondary-green">Payment Method:</p>
                          <p class="text-secondary-green">Transaction Status:</p>
                          <p class="text-secondary-green">Date:</p>
                      </div>
                      <div class="col-md-6 col-12">
                          <p class="text-primary-green fw-bold"><?= formatCurrency($transaction['total_amount']) ?></p>
                          <p class="text-primary-green fw-bold">Online Payment</p>
                          <p class="text-primary-green fw-bold"><?= $transaction['status'] ?></p>
                          <p class="text-primary-green fw-bold"><?= TimeDateUtils::create($transaction['created_at'])->toCustomFormat('F j, Y h:i') ?></p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Ticket Actions -->
      <div class="row g-4 justify-content-center mb-5">
          <div class="col-md-4">
              <div class="ticket-card bg-white p-4 rounded-3">
                  <i class="fas fa-download fa-3x text-primary-green mb-3"></i>
                  <h4 class="text-primary-green mb-3">Download Tickets</h4>
                  <div class="d-grid gap-2">
                      <a href="<?= URL_ROOT . "/tickets/download/{$transaction['transaction_id']}" ?>" class="btn btn-ticket">
                          <i class="fas fa-file-pdf me-2"></i>PDF Format
                      </a>
                      <!-- <button class="btn btn-outline-primary-green">
                          <i class="fab fa-apple me-2"></i>Apple Wallet
                      </button> -->
                  </div>
              </div>
          </div>
      </div>

      <!-- Event Details -->
      <div class="row justify-content-center shadow rounded-3">
          <div class="col-lg-8 col-12">
              <div class="bg-white p-4 rounded-3">
                  <h3 class="text-primary-green mb-4">Event Details</h3>
                  <div class="row g-4">
                      <div class="col-12 text-start">
                          <h5 class="text-primary-green"><?= $transaction['event_name'] ?></h5>
                          <p class="text-secondary-green">
                              <i class="fas fa-calendar me-2"></i><?= TimeDateUtils::create($transaction['date_time'])->toCustomFormat('F j, Y') ?><br>
                              <i class="fas fa-clock me-2"></i><?= TimeDateUtils::create($transaction['date_time'])->toCustomFormat('h:i A') ?><br>
                              <i class="fas fa-map-marker-alt me-2"></i><?= $transaction['location'] ?>
                          </p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Support -->
      <div class="mt-5 text-secondary-green">
          <p>Need help? <a href="mailto:<?= setting("mail", "support@eventlyy.com") ?>" class="text-primary-green">Contact our support team</a></p>
          <a href="<?= URL_ROOT . "/events" ?>" class="btn btn-buy">
              <i class="fas fa-calendar me-2"></i>Explore More Events
          </a>
      </div>
  </div>
</main>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>