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

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;


$transactionId = $details['transaction_id'] ?? null;
$ticketType = strtolower(trim($details['type'] ?? ''));
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
      border: 2px solid var(--light-green);
      transition: all 0.3s ease;
  }

  .timeline {
      border-left: 3px solid var(--light-green);
      margin-left: 1rem;
      padding-left: 2rem;
  }

  .map-preview {
      height: 200px;
      border-radius: 10px;
      overflow: hidden;
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
      <h1 class="text-primary-green mb-3">Ticket Found!</h1>
      <!-- <p class="lead text-secondary-green mb-5">Your tickets confirmed.</p> -->

      <!-- Transaction Details -->
      <div class="row justify-content-center mb-5">
          <div class="col-lg-6">
              <div class="bg-white p-4 rounded-3">
                  <div class="row text-start">
                      <div class="col-md-6">
                          <p class="text-secondary-green">Ticket Type:</p>
                          <p class="text-secondary-green">Ticket Qty:</p>
                          <p class="text-secondary-green">Assign To:</p>
                      </div>
                      <div class="col-md-6">
                          <p class="text-primary-green fw-bold"><?= $details['type'] ?></p>
                          <p class="text-primary-green fw-bold"><?= $details['quantity'] ?></p>
                          <p class="text-primary-green fw-bold"><?= $details['assign_to'] ?></p>
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
                      <a href="<?= $downloadUrl ?>" class="btn btn-primary-green">
                          <i class="fas fa-file-pdf me-2"></i>PDF Format
                      </a>
                      <!-- <button class="btn btn-outline-primary-green">
                          <i class="fab fa-apple me-2"></i>Apple Wallet
                      </button> -->
                  </div>
              </div>
          </div>
      </div>

      <!-- Next Steps -->
      <div class="row justify-content-center text-start mb-5">
          <div class="col-lg-8">
              <h3 class="text-primary-green mb-4">What's Next?</h3>
              <div class="timeline">
                  <div class="mb-4">
                      <h5 class="text-primary-green">1. Ticket Delivery</h5>
                      <p class="text-secondary-green">Your tickets have been sent to your email and are available in your account</p>
                  </div>

                  <div>
                      <h5 class="text-primary-green">2. Entry Instructions</h5>
                      <p class="text-secondary-green">Present your ticket QR code at the venue entrance</p>
                  </div>

                  <div>
                      <h5 class="text-primary-green">3. Identity Verification</h5>
                      <p class="text-secondary-green">Present a valid means of identification at the venue entrance. </br> ID with name and a photo.</p>
                  </div>
              </div>
          </div>
      </div>

      <!-- Event Details -->
      <div class="row justify-content-center">
          <div class="col-lg-8">
              <div class="bg-white p-4 rounded-3">
                  <h3 class="text-primary-green mb-4">Event Details</h3>
                  <div class="row g-4">
                      <div class="col-md-6 text-start">
                          <h5 class="text-primary-green"><?= $details['event_name'] ?></h5>
                          <p class="text-secondary-green">
                              <i class="fas fa-calendar me-2"></i><?= TimeDateUtils::create($details['date_time'])->toCustomFormat('F j, Y') ?><br>
                              <i class="fas fa-clock me-2"></i><?= TimeDateUtils::create($details['date_time'])->toCustomFormat('h:i A') ?><br>
                              <i class="fas fa-map-marker-alt me-2"></i><?= $details['location'] ?>
                          </p>
                      </div>
                      <div class="col-md-6">
                          <div class="map-preview">
                              <iframe
                                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.4664374888366!2d-73.96870218459313!3d40.78286477932539!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2589a018531e3%3A0xb9df1f7387a94119!2sCentral%20Park!5e0!3m2!1sen!2sus!4v1623940176789!5m2!1sen!2sus"
                                  style="border:0; height: 100%; width: 100%;"
                                  allowfullscreen=""
                                  loading="lazy">
                              </iframe>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Social Sharing -->
      <div class="mt-5">
          <h4 class="text-primary-green mb-3">Share Your Excitement!</h4>
          <div class="d-flex justify-content-center gap-3">
              <a href="<?= URL_ROOT . "/events/view/{$details['eventID']}" ?>" class="btn btn-outline-primary-green">
                  <i class="fab fa-facebook"></i>
              </a>
              <a href="#" class="btn btn-outline-primary-green">
                  <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="btn btn-outline-primary-green">
                  <i class="fab fa-whatsapp"></i>
              </a>
          </div>
      </div>

      <!-- Support -->
      <div class="mt-5 text-secondary-green">
          <p>Need help? <a href="mailto:<?= setting("mail", "support@eventlyy.com") ?>" class="text-primary-green">Contact our support team</a></p>
          <a href="<?= URL_ROOT . "/events" ?>" class="btn btn-primary-green">
              <i class="fas fa-calendar me-2"></i>Explore More Events
          </a>
      </div>
  </div>
</main>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>