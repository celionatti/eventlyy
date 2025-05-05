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
use celionatti\Bolt\Forms\BootstrapForm;

?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(OrganiserNavComponent::class); ?>

<!-- Header -->
<section class="page-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 col-lg-9">
        <h1 class="fw-bold mb-2">Attendee Details</h1>
        <p class="mb-0">Preview attendee details for your <?= $event['name'] ?> Event</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/attendees" ?>" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-arrow-left me-2"></i> Back to Attendees
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="content-section py-4">
  <div class="container">
    <div class="row">
          <div class="col-md-8">
            <h5 class="mb-4">Personal Information</h5>
            <div class="attendee-detail-row">
              <div class="detail-label">Name</div>
              <div class="detail-value attendee-name-display text-capitalize"><?= $info['first_name'] . " " . $info['last_name'] ?></div>
            </div>
            <div class="attendee-detail-row">
              <div class="detail-label">Email</div>
              <div class="detail-value"><?= $info['email'] ?></div>
            </div>
            <div class="attendee-detail-row">
              <div class="detail-label">Phone</div>
              <div class="detail-value"><?= $info['phone'] ?></div>
            </div>

            <div class="ticket-details">
              <h5 class="mb-3">Ticket Information</h5>
              <div class="attendee-detail-row">
                <div class="detail-label">Ticket Type</div>
                <div class="detail-value">
                  <span class="ticket-type-badge <?= get_status_class($info['ticket_type']) ?>"><?= $info['ticket_type'] ?></span>
                </div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Order ID</div>
                <div class="detail-value"><?= $info['ticket_token'] ?></div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Purchase Date</div>
                <div class="detail-value"><?= TimeDateUtils::create($info['purchase_date'])->toCustomFormat("F j, Y") ?></div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Price</div>
                <div class="detail-value"><?= formatCurrency($info['amount']) ?></div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                  <span class="status-badge text-capitalize <?= get_status_class($info['ticket_status']) ?>"><?= str_replace("_", " ", $info['ticket_status']) ?></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="qr-code border shadow py-3">
              <i class="fas fa-qrcode fa-5x text-success"></i>
              <p class="mt-2 text-center text-muted small">Attendee QR Code</p>
            </div>

            <div class="d-grid gap-2 mt-4">
              <?php if($info['ticket_status'] !== "checked_in"): ?>
              <form action="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/attendee/{$info['transactionId']}" ?>" method="post" class="d-inline-block" onsubmit="return confirm('Are you sure you want to check in ticket?');">
                <input type="hidden" name="status" value="checked_in">
                <button type="submit" class="btn btn-primary-action w-100" id="manualCheckInBtn">
                <i class="fas fa-clipboard-check"></i> Check In
                </button>
              </form>
              <?php endif; ?>

              <button class="btn btn-outline-action disabled">
                <i class="fas fa-envelope"></i> Send Email
              </button>

              <?php if($info['ticket_status'] !== "cancelled"): ?>
              <form action="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/attendee/{$info['transactionId']}" ?>" method="post" class="d-inline-block" onsubmit="return confirm('Are you sure you want to cancel ticket?');">
                <input type="hidden" name="status" value="cancelled">
                <button class="btn btn-outline-danger w-100">
                  <i class="fas fa-times-circle"></i> Cancel Ticket
                </button>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
  </div>
</section>

<?php $this->end() ?>

<?php $this->start("script") ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize tooltips
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

      // Handle search functionality
      const searchInput = document.querySelector('.search-bar input');
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const attendeeRows = document.querySelectorAll('.attendees-table tbody tr');

        attendeeRows.forEach(row => {
          const name = row.querySelector('.attendee-name').textContent.toLowerCase();
          const email = row.querySelector('.attendee-email').textContent.toLowerCase();
          const orderId = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

          if (name.includes(searchTerm) || email.includes(searchTerm) || orderId.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });

      // Handle filter functionality
      const ticketFilter = document.querySelector('.filter-dropdown:nth-child(1)');
      const statusFilter = document.querySelector('.filter-dropdown:nth-child(2)');

      function applyFilters() {
        const ticketValue = ticketFilter.value;
        const statusValue = statusFilter.value;
        const attendeeRows = document.querySelectorAll('.attendees-table tbody tr');

        attendeeRows.forEach(row => {
          let showRow = true;

          // Apply ticket filter
          if (ticketValue !== 'all') {
            const ticketType = row.querySelector('.ticket-type-badge').classList.contains(ticketValue) ||
                              (ticketValue === 'early' && row.querySelector('.ticket-type-badge').classList.contains('early-bird'));
            if (!ticketType) showRow = false;
          }

          // Apply status filter
          if (statusValue !== 'all' && showRow) {
            const status = row.querySelector('.status-badge').classList.contains(statusValue);
            if (!status) showRow = false;
          }

          row.style.display = showRow ? '' : 'none';
        });
      }

      ticketFilter.addEventListener('change', applyFilters);
      statusFilter.addEventListener('change', applyFilters);
    });
  </script>
<?php $this->end() ?>
