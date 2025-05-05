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

use PhpStrike\app\components\OrganiserNavComponent;
use PhpStrike\app\components\FooterComponent;

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(OrganiserNavComponent::class); ?>

<!-- Header -->
<section class="page-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 col-lg-9">
        <h1 class="fw-bold mb-2">Manage Events</h1>
        <p class="mb-0">manage all of your events</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/create" ?>" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-plus-circle me-2"></i> Create Event
        </a>
      </div>
    </div>
  </div>
</section>

<?php if($events): ?>
<section class="events-section">
  <div class="container">
    <!-- View Controls -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-5">
        <h5 class="mb-0">Showing <?= $count ?> events</h5>
      </div>
      <div class="col-md-7">
        <div class="d-flex justify-content-md-end flex-wrap gap-2">
          <div class="view-toggle ms-2 d-none d-md-flex">
            <button class="btn active me-1" id="gridView"><i class="fas fa-th-large"></i></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Grid View (Default) -->
    <div id="eventsGridView">
      <div class="row">
        <!-- Event Card -->
        <?php foreach ($events as $event): ?>
        <div class="col-md-6 col-lg-4">
          <div class="event-card">
            <div class="event-image" style="background-image: url('/<?= $event['image'] ?>')">
              <div class="event-date-badge">
                <i class="far fa-calendar-alt me-2"></i> <?= TimeDateUtils::create($event['date_time'])->toCustomFormat("M j, Y") ?>
              </div>
              <div class="event-type-badge">
                <?= $event['tags'] ?>
              </div>
            </div>
            <div class="event-details">
              <h5 class="event-title"><?= $event['name'] ?></h5>
              <div class="event-location">
                <i class="fas fa-map-marker-alt"></i> <?= $event['location'] ?>
              </div>
              <div class="event-meta">
                <div class="ticket-price">
                  Status: <?= $event['status'] ?>
                </div>
                <div class="event-actions">
                  <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}" ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
      <nav aria-label="Page navigation">
        <?= $pagination ?>
      </nav>
    </div>
  </div>
</section>
<?php else: ?>
  <div class="no-events">
    <div class="no-events-icon">
      <i class="far fa-calendar-times"></i>
    </div>
    <h3>No Events Found</h3>
    <p class="text-muted">We couldn't find any events matching your search criteria.</p>
    <p class="text-muted mb-4">Try adjusting your filters or create your first event.</p>
    <div class="d-flex justify-content-center gap-3">
      <button class="btn btn-outline-primary">
        <i class="fas fa-filter me-2"></i>Clear All Filters
      </button>
      <a href="<?= URL_ROOT . "/organiser/events/create" ?>" class="btn btn-success" style="background-color: var(--primary-green); border-color: var(--primary-green);">
        <i class="fas fa-plus-circle me-2"></i>Create New Event
      </a>
    </div>
  </div>
<?php endif; ?>

<?php $this->end() ?>
