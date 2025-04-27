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
      <div class="col-md-8">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="text-white text-decoration-none">My Events</a></li>
            <li class="breadcrumb-item active text-white-50" aria-current="page">Event Details</li>
          </ol>
        </nav>
        <h1 class="fw-bold mb-0">Event Management</h1>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="btn btn-outline-light me-2">
          <i class="fas fa-arrow-left me-2"></i> Back to Events
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Event Details -->
<section class="event-details-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <!-- Event Hero Image -->
        <div class="event-hero" style="background-image: url('/<?= $event['image'] ?>')">
          <div class="event-hero-overlay"></div>
          <div class="event-type-badge text-uppercase">
            <?= $event['status'] ?>
          </div>
          <div class="event-host-actions">
            <button class="btn btn-primary btn-sm me-2">
              <i class="fas fa-eye me-1"></i> View Public Page
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mb-2">
        <div class="event-details-container">
          <div class="event-content">
            <!-- Event Header -->
            <div class="event-header">
              <h2 class="event-title"><?= $event['name'] ?></h2>
              <div class="event-meta">
                <div class="event-meta-item">
                  <i class="far fa-calendar-alt"></i> <?= TimeDateUtils::create($event['date_time'])->toCustomFormat("F j, Y") ?> • <?= TimeDateUtils::create($event['date_time'])->toCustomFormat("h:i a") ?>
                </div>
                <div class="event-meta-item">
                  <i class="fas fa-map-marker-alt"></i> <?= $event['location'] ?>
                </div>
                <div class="event-meta-item">
                  <i class="fas fa-tag"></i> <?= $event['tags'] ?>
                </div>
              </div>
            </div>

            <!-- Description -->
            <h4 class="section-title">Event Description</h4>
            <div class="event-description">
              <?= StringUtils::create(htmlspecialchars_decode(nl2br($event['description']))) ?>
            </div>

            <?php if($tickets): ?>
            <!-- Ticket Information -->
            <h4 class="section-title">Ticket Information</h4>
            <div class="ticket-info">
              <?php foreach($tickets as $ticket): ?>
              <div class="ticket-type">
                <span class="ticket-name"><?= $ticket['type'] ?></span>
                <span class="ticket-price"><?= formatCurrency($ticket['price']) ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Location -->
            <h4 class="section-title">Location</h4>
            <div class="event-location-map">
              <img src="<?= get_image("") ?>" alt="Event Location Map" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Event Statistics -->
            <h4 class="section-title">Event Statistics</h4>
            <div class="event-stats">
              <div class="row">
                <div class="col-6 col-md-3">
                  <div class="stats-item">
                    <div class="stats-number">245</div>
                    <div class="stats-label">Tickets Sold</div>
                  </div>
                </div>

                <div class="col-6 col-md-3">
                  <div class="stats-item">
                    <div class="stats-number">$9,850</div>
                    <div class="stats-label">Total Sales</div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Host Actions -->
            <h4 class="section-title">Host Actions</h4>
            <div class="action-buttons d-flex flex-wrap gap-2">
              <a href="<?= URL_ROOT . "/organiser/events/edit/{$event['event_id']}" ?>" class="btn btn-edit">
                <i class="fas fa-edit me-2"></i> Edit Event
              </a>
              <button class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteEventModal">
                <i class="fas fa-trash-alt me-2"></i> Delete Event
              </button>

              <button class="btn btn-dark">
                <i class="fas fa-pause me-2"></i> Pause Ticket Sales
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 mt-4 mt-lg-0">
        <!-- Quick Actions Card -->
        <div class="side-card">
          <h5 class="mb-4">Quick Actions</h5>
          <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/ticket" ?>" class="dashboard-link">
            <i class="fas fa-ticket-alt"></i> Manage Tickets
          </a>
          <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/attendees" ?>" class="dashboard-link">
            <i class="fas fa-users"></i> View Attendees
          </a>
          <a href="#" class="dashboard-link">
            <i class="fas fa-share-alt"></i> Promote Event
          </a>
        </div>

        <!-- Event Timeline Card -->
        <div class="side-card">
          <h5 class="mb-4">Event Timeline</h5>
          <div class="timeline">
            <div class="d-flex mb-3">
              <div class="me-3">
                <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                <div class="bg-light" style="width: 2px; height: 30px; margin-left: 5px;"></div>
              </div>
              <div>
                <small class="text-muted">March 1, 2025</small>
                <div>Event Created</div>
              </div>
            </div>
            <div class="d-flex mb-3">
              <div class="me-3">
                <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                <div class="bg-light" style="width: 2px; height: 30px; margin-left: 5px;"></div>
              </div>
              <div>
                <small class="text-muted">March 5, 2025</small>
                <div>Tickets Published</div>
              </div>
            </div>
            <div class="d-flex mb-3">
              <div class="me-3">
                <div class="bg-primary rounded-circle" style="width: 12px; height: 12px;"></div>
                <div class="bg-light" style="width: 2px; height: 30px; margin-left: 5px;"></div>
              </div>
              <div>
                <small class="text-muted">Today</small>
                <div>Ticket Sales Active</div>
              </div>
            </div>
            <div class="d-flex">
              <div class="me-3">
                <div class="bg-light rounded-circle border" style="width: 12px; height: 12px;"></div>
              </div>
              <div>
                <small class="text-muted">April 25, 2025</small>
                <div>Event Date</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Delete Event Modal -->
<div class="modal fade" id="deleteEventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteEventModalLabel">Delete Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
        </div>
        <h5 class="text-center mb-3">Are you sure you want to delete this event?</h5>
        <p class="text-center text-muted">This action cannot be undone. All event data, including ticket sales, attendee information, and analytics will be permanently removed.</p>

        <div class="alert alert-warning mt-3">
          <i class="fas fa-info-circle me-2"></i> Note: If you have sold tickets for this event, consider pausing ticket sales instead of deleting the event.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="<?= URL_ROOT . "/organiser/events/delete/{$event['event_id']}" ?>" method="post" class="m-1 d-inline-block">
          <button type="submit" class="btn btn-danger">Delete Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->end() ?>
