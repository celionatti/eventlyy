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
        <h1 class="fw-bold mb-2">Attendees</h1>
        <p class="mb-0">Manage attendees for your <?= $event['name'] ?></p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}" ?>" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-arrow-left me-2"></i> Back to Event
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="content-section py-4">
  <div class="container">
    <!-- Stats Cards -->
    <div class="stats-row">
      <div class="stat-card tickets">
        <div class="stat-value">626</div>
        <p class="stat-label">Total Tickets Sold</p>
      </div>
      <div class="stat-card revenue">
        <div class="stat-value">$26,035</div>
        <p class="stat-label">Total Revenue</p>
      </div>
      <div class="stat-card checkins">
        <div class="stat-value">127</div>
        <p class="stat-label">Checked In</p>
      </div>
    </div>

    <!-- Attendee List -->
    <div class="content-container">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h3 class="section-title mb-0">Attendees List</h3>

        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-primary-action" data-bs-toggle="modal" data-bs-target="#scanModal">
            <i class="fas fa-qrcode"></i> Check-In Attendee
          </button>
          <div class="dropdown export-dropdown">
            <button class="btn btn-outline-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-download"></i> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel export-icon"></i> Export to Excel</a></li>
              <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv export-icon"></i> Export to CSV</a></li>
              <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf export-icon"></i> Export to PDF</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Filter and Search -->
      <div class="filter-bar">
        <div class="filter-group">
          <select class="form-select filter-dropdown">
            <option value="all" selected>All Tickets</option>
            <option value="general">General Admission</option>
            <option value="vip">VIP Experience</option>
            <option value="early">Early Bird Special</option>
            <option value="workshop">Workshop Pass</option>
          </select>
          <select class="form-select filter-dropdown">
            <option value="all" selected>All Statuses</option>
            <option value="confirmed">Confirmed</option>
            <option value="pending">Pending</option>
            <option value="checked-in">Checked In</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="search-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" class="form-control" placeholder="Search attendees...">
        </div>
      </div>

      <?php if($ticket_transactions): ?>
      <!-- Table -->
      <div class="table-container">
        <table class="attendees-table">
          <thead>
            <tr>
              <th>Attendee</th>
              <th>Ticket</th>
              <th>Purchase Date</th>
              <th>Ticket Token</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($ticket_transactions as $transaction): ?>
            <!-- Attendee -->
            <tr>
              <td>
                <div class="attendee-name"><?= $transaction['first_name'] . " " . $transaction['last_name'] ?></div>
                <div class="attendee-email"><?= $transaction['email'] ?></div>
              </td>
              <td>
                <span class="ticket-type-badge <?= get_status_class($transaction['ticket_type']) ?>"><?= $transaction['ticket_type'] ?></span>
              </td>
              <td><?= TimeDateUtils::create($transaction['purchase_date'])->toCustomFormat("M j, Y") ?></td>
              <td><?= $transaction['ticket_token'] ?></td>
              <td>
                <span class="status-badge <?= get_status_class($transaction['ticket_status']) ?>"><?= $transaction['ticket_status'] ?></span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/attendee/{$transaction['transactionId']}" ?>" class="action-btn" data-attendee="Sarah Johnson">
                    <i class="fas fa-eye"></i>
                  </a>

                  <button class="action-btn text-danger" data-bs-toggle="tooltip" title="Cancel Ticket">
                    <i class="fas fa-times-circle"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav aria-label="Attendee list pagination">
        <?= $pagination ?>
      </nav>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- QR Scanner Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mt-2">
          <p class="text-center">Enter ticket code manually:</p>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Enter ticket code..." id="manualTicketCode">
            <button class="btn btn-primary-action" id="submitTicketCode">Check In</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
