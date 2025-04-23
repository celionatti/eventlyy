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

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(NavComponent::class); ?>

<section class="hero-section">
  <div class="container text-center">
    <h1 class="display-5 fw-bold text-white mb-4">Discover Events & Get Tickets</h1>
    <p class="lead text-white mb-5">Find and book tickets for the best experiences near you</p>

    <!-- Search Bar -->
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8 px-4">
        <form action="#" class="search-container">
          <div class="input-group">
            <input type="text" class="form-control search-input" name="query" placeholder="Search for events...">
            <button class="btn search-btn" type="submit">
              <i class="fas fa-search me-1"></i> <span class="btn-text">Find Events</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container py-3">
    <div class="row align-items-center g-4 py-3">
      <div class="col-md-5 d-flex flex-column align-items-start gap-2 px-4">
        <h2 class="fw-bold" style="color: var(--dark-blue);">Discover Events By Category</h2>
        <p class="text-muted">Browse an extensive range of events and experiences at your fingertips.</p>
        <a href="<?= URL_ROOT . "/events" ?>" class="btn" style="background-color: var(--primary-green); color: white; padding: 10px 25px; border-radius: 30px;">Explore All Events</a>
      </div>

      <div class="col-md-7">
        <div class="row row-cols-2 g-3 px-2">
          <div class="col-6">
            <div class="feature-card p-3 p-md-4 text-center">
              <i class="fa-solid fa-music fa-2x category-icon"></i>
              <h5 class="fw-semibold mb-0 fs-6 fs-md-5">Music & Concerts</h5>
            </div>
          </div>

          <div class="col-6">
            <div class="feature-card p-3 p-md-4 text-center">
              <i class="fa-solid fa-people-group fa-2x category-icon"></i>
              <h5 class="fw-semibold mb-0 fs-6 fs-md-5">Community</h5>
            </div>
          </div>

          <div class="col-6">
            <div class="feature-card p-3 p-md-4 text-center">
              <i class="fa-solid fa-briefcase fa-2x category-icon"></i>
              <h5 class="fw-semibold mb-0 fs-6 fs-md-5">Business</h5>
            </div>
          </div>

          <div class="col-6">
            <div class="feature-card p-3 p-md-4 text-center">
              <i class="fa-solid fa-palette fa-2x category-icon"></i>
              <h5 class="fw-semibold mb-0 fs-6 fs-md-5">Arts & Theater</h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Events Section -->
<?php if($featured): ?>
<section class="featured-events">
  <div class="container py-3">
    <h2 class="text-center mb-4 fw-bold" style="color: var(--dark-blue);">Featured Events</h2>
    <div class="row px-3">
      <?php foreach($featured as $event): ?>
      <div class="col-12 col-md-6 col-lg-4 mb-4">
        <div class="card event-card">
          <img src="<?= get_image($event['image'], "default") ?>" class="card-img-top event-img" alt="Event image">
          <div class="card-body">
            <span class="event-date mb-2"><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("M j, Y") ?></span>
            <h5 class="card-title mt-2"><?= $event['name'] ?></h5>
            <p class="card-text text-muted mb-2"><?= $event['location'] ?></p>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <span class="event-price"><?= $event['lowest_price'] === "0.00" ? "Free" : formatCurrency($event['lowest_price']) ?></span>
              <a href="<?= URL_ROOT . "/events/view/{$event['event_id']}" ?>" class="btn btn-buy">Buy Tickets</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-3">
      <a href="<?= URL_ROOT . "/events" ?>" class="btn" style="border: 2px solid var(--primary-green); color: var(--primary-green); border-radius: 30px; padding: 10px 30px;">View All Events</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- How It Works Section -->
<section class="py-5">
  <div class="container py-3">
    <h2 class="text-center mb-4 fw-bold" style="color: var(--dark-blue);">How It Works</h2>
    <div class="row g-4 px-3">
      <div class="col-12 col-md-4 mb-3">
        <div class="step-card">
          <div class="step-number">1</div>
          <h4>Find Events</h4>
          <p class="text-muted">Search for events by category, date, or location to find your perfect experience.</p>
        </div>
      </div>

      <div class="col-12 col-md-4 mb-3">
        <div class="step-card">
          <div class="step-number">2</div>
          <h4>Book Tickets</h4>
          <p class="text-muted">Select your tickets, pick your seats, and check out securely online.</p>
        </div>
      </div>

      <div class="col-12 col-md-4 mb-3">
        <div class="step-card">
          <div class="step-number">3</div>
          <h4>Enjoy the Event</h4>
          <p class="text-muted">Get your e-tickets instantly and show them at the venue on event day.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
  <div class="container py-4 text-center">
    <h2 class="text-white mb-3">Ready to host your own event?</h2>
    <p class="text-white mb-4">Create an account and start selling tickets in minutes.</p>
    <a href="<?= URL_ROOT . "/dashboard" ?>" class="btn btn-cta">Start Selling Tickets</a>
  </div>
</section>

<?= renderComponent(FooterComponent::class); ?>

<?php $this->end() ?>