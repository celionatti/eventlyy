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

use celionatti\Bolt\Forms\BootstrapForm;
use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;


?>

<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<!-- Event Hero Section -->
<section class="event-hero">
    <div class="container">
      <!-- Breadcrumbs -->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= URL_ROOT ?>">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= URL_ROOT . "/events" ?>">Events</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?= $event['name'] ?></li>
        </ol>
      </nav>

      <div class="mt-4">
        <span class="event-date-badge"><i class="fas fa-calendar me-2"></i><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("M j, Y") ?></span>
        <span class="event-category-badge"><i class="fas fa-tags me-2"></i><?= $event['tags'] ?></span>
      </div>
      <h1 class="display-5 fw-bold mt-3"><?= $event['name'] ?></h1>
      <p class="lead mb-0"><i class="fas fa-map-marker-alt me-2"></i><?= $event['location'] ?></p>
      <p class="lead"><i class="fas fa-clock me-2"></i><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("l, h:i a") ?></p>

      <div class="mt-4">
        <a href="https://twitter.com/intent/tweet?url=<?= URL_ROOT . "/events/view/{$event['event_id']}" ?>&text=<?= $event['name'] ?>" class="btn btn-share"><i class="fa-brands fa-square-x-twitter text-secondary-green"></i></a>
        <a href="https://api.whatsapp.com/send?text=<?= $event['name'] ?>: <?= URL_ROOT . "/events/view/{$event['event_id']}" ?>" class="btn btn-share"><i class="fa-brands fa-whatsapp"></i></a>
        <!-- <button class="btn btn-share"><i class="far fa-heart me-2"></i>Save</button> -->
      </div>
    </div>
</section>

<!-- Main Content -->
<div class="container mt-5">
<div class="row">
  <!-- Event Details -->
  <div class="col-lg-8">
    <img src="<?= get_image($event['image'], "default") ?>" class="event-image mb-4" alt="<?= $event['name'] ?>">

    <div class="my-5">
      <h3 class="section-heading">About This Event</h3>
      <?= StringUtils::create(htmlspecialchars_decode(nl2br($event['description']))) ?>

      <p class="mt-4"><em>* Schedule subject to change. Please check the event socials, news here, or mail for real-time updates.</em></p>
    </div>

    <div class="my-5">
      <h3 class="section-heading">Location & Venue</h3>
      <div class="d-flex align-items-center mb-3">
        <div class="event-info-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div>
          <h5 class="mb-0"><?= $event['location'] ?></h5>
          <p class="text-muted mb-0"><?= $event['location_tips'] ?? "" ?></p>
        </div>
      </div>
      <div class="map-container">
        <img src="<?= get_image("") ?>" alt="Map" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
      <div class="mt-3">
        <p><i class="fas fa-info-circle me-2 text-primary"></i> <?= $event['location_info'] ?? "" ?></p>
      </div>
    </div>

    <div class="event-organizer">
      <div class="d-flex align-items-center">
        <img src="<?= get_image("", "avatar") ?>" class="organizer-img me-3" alt="Organizer">
        <div>
          <h5 class="mb-1">Event Organizer</h5>
          <!-- <p class="text-muted mb-2">Event Organizer</p> -->
          <p class="text-muted mb-2"><i class="fas fa-phone fa-md"></i> <?= $event['phone'] ?></p>
          <p class="text-muted mb-2"><i class="fas fa-envelope fa-md"></i> <?= $event['mail'] ?></p>
        </div>
      </div>
    </div>

  </div>

  <!-- Ticket Options Sidebar -->
  <div class="col-lg-4">
    <div class="ticket-card">
      <div class="ticket-header">
        <h4 class="mb-0">Get Tickets</h4>
      </div>
      <div class="card-body p-2">
        <!-- <p class="text-muted"><i class="fas fa-tag me-2"></i>31% of tickets sold</p> -->
        <?php if($tickets): ?>
        <form action="<?= URL_ROOT . "/events/tickets/{$event['event_id']}/contact" ?>" method="GET">
          <div class="ticket-option">
            <?= BootstrapForm::selectField("Ticket Type", "ticket_id", '', $ticketOpts, ['class' => 'form-select'], ['class' => 'col-md-12 mb-3'], $errors) ?>

            <?= BootstrapForm::selectField("Quantity", "quantity", '', $quantityOpts, ['class' => 'form-select', 'type' => 'number'], ['class' => 'col-md-12 mb-3'], $errors) ?>
          </div>

          <button class="btn btn-ticket" type="submit">
            <i class="fas fa-ticket-alt me-2"></i>Get Tickets Now
          </button>
        </form>
        <?php else: ?>
          <h4 class="small text-danger text-center mt-3">No Ticket Available Yet.</h4>
        <?php endif; ?>
        <div class="mt-4">
          <p class="small mb-2"><i class="fas fa-shield-alt me-2 text-success"></i>Secure checkout</p>
          <p class="small mb-0 text-muted">Tickets will be emailed immediately after purchase</p>
        </div>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="card mt-4 border-0 shadow-sm">
      <div class="card-body">
        <h5 class="mb-3">Event Information</h5>
        <div class="d-flex align-items-start mb-3">
          <div class="event-info-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div>
            <h6 class="mb-0">Date and Time</h6>
            <p class="text-muted mb-0"><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("l, F j, Y") ?><br><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("h:i a") ?></p>
          </div>
        </div>
        <div class="d-flex align-items-start mb-3">
          <div class="event-info-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <div>
            <h6 class="mb-0">Location</h6>
            <p class="text-muted mb-0"><?= $event['location'] ?></p>
          </div>
        </div>
       <!--  <div class="d-flex align-items-start">
          <div class="event-info-icon">
            <i class="fas fa-user-friends"></i>
          </div>
          <div>
            <h6 class="mb-0">Attendees</h6>
            <p class="text-muted mb-0">1,245 going · 3,500 interested</p>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</div>

<?php if($recents): ?>
<!-- Related Events -->
<div class="my-5">
  <h3 class="section-heading">Similar Events You Might Like</h3>
  <div class="row">
    <!-- Related Event -->
    <?php foreach($recents as $recent): ?>
    <div class="col-md-4 mb-4">
      <div class="card related-event-card">
        <img src="<?= get_image($recent['image'], "default") ?>" class="related-event-img" alt="Jazz Night" loading="lazy">
        <div class="card-body">
          <div class="mb-2">
            <span class="related-event-date"><?= TimeDateUtils::create($recent['date_time'])->toCustomFormat("M j, Y") ?></span>
            <span class="related-event-category"><?= $recent['tags'] ?></span>
          </div>
          <h5 class="card-title"><?= $recent['name'] ?></h5>
          <p class="card-text text-muted small">
              <?= StringUtils::create(htmlspecialchars_decode(nl2br($recent['description'])))->excerpt(70) ?>
          </p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold"><?= $recent['lowest_price'] === "0.00" ? "Free" : formatCurrency($recent['lowest_price']) ?></span>
            <a href="<?= URL_ROOT . "/events/view/{$recent['event_id']}" ?>" class="btn btn-sm btn-outline-primary">View Event</a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
</div>

<!-- Newsletter Section -->
<section class="py-5 bg-light">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8 text-center">
      <h3>Never Miss an Event</h3>
      <p class="text-muted mb-4">Subscribe to our newsletter and be the first to know about upcoming events and exclusive offers.</p>
      <div class="input-group mb-3 mx-auto" style="max-width: 500px;">
        <input type="email" class="form-control" placeholder="Your email address" aria-label="Email" aria-describedby="subscribe-btn">
        <button class="btn btn-primary" type="button" id="subscribe-btn" style="background-color: var(--primary-green); border-color: var(--primary-green);">Subscribe</button>
      </div>
      <p class="small text-muted">By subscribing, you agree to receive marketing emails from Eventlyy</p>
    </div>
  </div>
</div>
</section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>