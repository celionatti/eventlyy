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
        <p class="mb-0">Manage attendees for your Summer Music Festival</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="#" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-question-circle me-2"></i> Get Help
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

      <!-- Table -->
      <div class="table-container">
        <table class="attendees-table">
          <thead>
            <tr>
              <th>Attendee</th>
              <th>Ticket</th>
              <th>Purchase Date</th>
              <th>Order ID</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Attendee 1 -->
            <tr>
              <td>
                <div class="attendee-name">Sarah Johnson</div>
                <div class="attendee-email">sarah.j@example.com</div>
              </td>
              <td>
                <span class="ticket-type-badge vip">VIP Experience</span>
              </td>
              <td>Apr 22, 2025</td>
              <td>#ORD-7825</td>
              <td>
                <span class="status-badge checked-in">Checked In</span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <button class="action-btn" data-bs-toggle="modal" data-bs-target="#detailsModal" data-attendee="Sarah Johnson">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button class="action-btn" data-bs-toggle="tooltip" title="Send Email">
                    <i class="fas fa-envelope"></i>
                  </button>
                  <button class="action-btn text-danger" data-bs-toggle="tooltip" title="Cancel Ticket">
                    <i class="fas fa-times-circle"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav aria-label="Attendee list pagination">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</section>

<!-- Attendee Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Attendee Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <h5 class="mb-4">Personal Information</h5>
            <div class="attendee-detail-row">
              <div class="detail-label">Name</div>
              <div class="detail-value attendee-name-display">Sarah Johnson</div>
            </div>
            <div class="attendee-detail-row">
              <div class="detail-label">Email</div>
              <div class="detail-value">sarah.j@example.com</div>
            </div>
            <div class="attendee-detail-row">
              <div class="detail-label">Phone</div>
              <div class="detail-value">(555) 123-4567</div>
            </div>

            <div class="ticket-details">
              <h5 class="mb-3">Ticket Information</h5>
              <div class="attendee-detail-row">
                <div class="detail-label">Ticket Type</div>
                <div class="detail-value">
                  <span class="ticket-type-badge vip">VIP Experience</span>
                </div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Order ID</div>
                <div class="detail-value">#ORD-7825</div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Purchase Date</div>
                <div class="detail-value">Apr 22, 2025</div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Price</div>
                <div class="detail-value">$149.99</div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                  <span class="status-badge checked-in">Checked In</span>
                </div>
              </div>
              <div class="attendee-detail-row">
                <div class="detail-label">Check-in Time</div>
                <div class="detail-value">Apr 26, 2025 10:23 AM</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="qr-code">
              <img src="/api/placeholder/200/200" alt="QR Code" class="img-fluid">
              <p class="mt-2 text-center text-muted small">Attendee QR Code</p>
            </div>

            <div class="d-grid gap-2 mt-4">
              <button class="btn btn-primary-action" id="manualCheckInBtn">
                <i class="fas fa-clipboard-check"></i> Check In
              </button>
              <button class="btn btn-outline-action">
                <i class="fas fa-envelope"></i> Send Email
              </button>
              <button class="btn btn-outline-action">
                <i class="fas fa-print"></i> Print Ticket
              </button>
              <button class="btn btn-outline-danger">
                <i class="fas fa-times-circle"></i> Cancel Ticket
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="confirmationModalBody">
        Are you sure you want to cancel this ticket?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Go Back</button>
        <button type="button" class="btn btn-danger" id="confirmActionBtn">Yes, Cancel Ticket</button>
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
