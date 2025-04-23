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


<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<!-- Events Header Section -->
<section class="events-header">
    <div class="container text-center">
      <h1 class="display-5 fw-bold text-white mb-4"><?= $filter['name'] ?></h1>
      <p class="lead text-white mb-5">Events Category</p>

      <!-- Search Bar -->
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 px-4">
          <form action="<?= URL_ROOT . "/events/search" ?>" method="GET" class="search-container">
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

<!-- Main Content -->
<div class="container mt-4">
    <!-- Category Filter Buttons -->
    <div class="category-buttons">
      <a href="<?= URL_ROOT . "/events" ?>" class="btn btn-category <?= (active_nav(1, "events")) ? "" : "active" ?>">All Events</a>
      <?php if($categories): ?>
        <?php foreach($categories as $category): ?>
          <a href="<?= URL_ROOT . "/events/categories/{$category['category_id']}" ?>" class="btn btn-category <?= (active_nav(3, "{$category['category_id']}")) ? "active" : "" ?>"><?= $category['name'] ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="row">
      <!-- Filters -->
      <div class="col-lg-3 mb-4">
        <div class="filter-section sticky-lg-top" style="top: 100px;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="filter-heading mb-0">Filters</h4>
            <a href="#" class="reset-filters">Reset All</a>
          </div>

          <!-- Date Filter -->
          <div class="mb-4">
            <h5 class="filter-heading">Date</h5>
            <div class="filter-item">
              <input type="radio" name="date" id="any-date" class="filter-checkbox" checked>
              <label for="any-date">Any Date</label>
            </div>
            <div class="filter-item">
              <input type="radio" name="date" id="today" class="filter-checkbox">
              <label for="today">Today</label>
            </div>
            <div class="filter-item">
              <input type="radio" name="date" id="this-weekend" class="filter-checkbox">
              <label for="this-weekend">This Weekend</label>
            </div>
            <div class="filter-item">
              <input type="radio" name="date" id="this-month" class="filter-checkbox">
              <label for="this-month">This Month</label>
            </div>
            <div class="filter-item">
              <div class="d-flex align-items-center">
                <input type="radio" name="date" id="custom-date" class="filter-checkbox">
                <label for="custom-date" class="me-2">Custom:</label>
              </div>
              <div class="mt-2">
                <input type="date" class="form-control form-control-sm" disabled>
              </div>
            </div>
          </div>

          <button class="btn search-btn w-100 mt-4">Apply Filters</button>
        </div>
      </div>

      <!-- Events Listing -->
      <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 sort-container">
          <div>
            <h5 style="color: var(--dark-blue);"><?= $count ?> Events Found</h5>
          </div>

        </div>

        <div class="row">
        <?php if($events): ?>
          <!-- Event Card -->
          <?php foreach($events as $event): ?>
          <div class="col-md-6 col-xl-4">
            <div class="card event-card">
              <img src="<?= get_image($event['image'], "default") ?>" class="card-img-top event-img" alt="<?= $event['name'] ?>" loading="lazy">
              <div class="card-body">
                <div class="mb-2">
                  <span class="event-date"><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("M j, Y") ?></span>
                  <span class="event-category"><?= $event['tags'] ?></span>
                </div>
                <h5 class="card-title mt-2"><?= $event['name'] ?></h5>
                <p class="card-text text-muted mb-1"><?= $event['location'] ?></p>
                <p class="card-text small mb-3"><i class="fas fa-calendar me-1"></i> <?= TimeDateUtils::create($event['date_time'])->toCustomFormat("D, h:i a") ?></p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="event-price"><?= $event['lowest_price'] === "0.00" ? "Free" : formatCurrency($event['lowest_price']) ?></span>
                  <a href="<?= URL_ROOT . "/events/view/{$event['event_id']}" ?>" class="btn btn-buy">Buy Tickets</a>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          <nav aria-label="Page navigation">
            <?= $pagination ?>
          </nav>
        </div>
      </div>
    </div>
</div>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>