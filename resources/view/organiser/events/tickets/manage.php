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

<?php $this->start('header') ?>
<style>
    /* Content container */
    .content-container {
      background-color: var(--white);
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      padding: 30px;
      margin-bottom: 30px;
    }

    /* Ticket cards */
    .ticket-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
      position: relative;
    }

    .ticket-card:hover {
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .ticket-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 15px;
    }

    .ticket-name {
      font-weight: 600;
      font-size: 1.25rem;
      color: var(--dark-blue);
      margin-bottom: 5px;
    }

    .ticket-type {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
    }

    .ticket-type.paid {
      background-color: var(--primary-green);
      color: white;
    }

    .ticket-type.free {
      background-color: var(--light-blue);
      color: var(--dark-blue);
    }

    .ticket-type.donation {
      background-color: #FFE0B2;
      color: #E65100;
    }

    .ticket-price {
      font-size: 1.1rem;
      font-weight: 600;
      color: #333;
    }

    .ticket-details {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin-bottom: 15px;
    }

    .detail-item {
      display: flex;
      flex-direction: column;
    }

    .detail-label {
      font-size: 0.85rem;
      color: #6c757d;
      margin-bottom: 3px;
    }

    .detail-value {
      font-weight: 500;
    }

    .ticket-status {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .status-indicator {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 5px;
    }

    .status-active {
      background-color: var(--primary-green);
    }

    .status-draft {
      background-color: #FFC107;
    }

    .status-ended {
      background-color: #6c757d;
    }

    .ticket-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    /* Action buttons */
    .btn-action {
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-edit {
      color: var(--dark-blue);
      border: 1px solid var(--dark-blue);
      background-color: transparent;
    }

    .btn-edit:hover {
      background-color: var(--light-blue);
      color: var(--dark-blue);
    }

    .btn-delete {
      color: #dc3545;
      border: 1px solid #dc3545;
      background-color: transparent;
    }

    .btn-delete:hover {
      background-color: #dc3545;
      color: white;
    }

    .btn-primary-action {
      background-color: var(--primary-green);
      border-color: var(--primary-green);
      color: white;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 500;
    }

    .btn-primary-action:hover {
      background-color: #5f9960;
      border-color: #5f9960;
      color: white;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 50px 20px;
    }

    .empty-icon {
      font-size: 5rem;
      color: #d1d1d1;
      margin-bottom: 20px;
    }

    /* Modal */
    .modal-content {
      border-radius: 15px;
      border: none;
    }

    .modal-header {
      border-bottom: 2px solid var(--light-blue);
      padding: 20px 25px;
    }

    .modal-title {
      color: var(--dark-blue);
      font-weight: 600;
    }

    .modal-body {
      padding: 25px;
    }

    .modal-footer {
      border-top: none;
      padding: 15px 25px 25px;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
      .ticket-details {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 767.98px) {
      .content-container {
        padding: 20px 15px;
      }

      .filter-bar {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
      }

      .search-bar {
        max-width: 100%;
      }

      .ticket-header {
        flex-direction: column;
      }

      .ticket-status {
        position: static;
        margin-top: 10px;
        margin-bottom: 10px;
      }
    }

    @media (max-width: 575.98px) {
      .ticket-details {
        grid-template-columns: 1fr;
      }

      .ticket-actions {
        flex-direction: column;
        gap: 10px;
      }

      .ticket-actions .btn {
        width: 100%;
      }
    }
  </style>
<?php $this->end() ?>

<?php $this->start('content') ?>

<?= renderComponent(OrganiserNavComponent::class); ?>

<!-- Header -->
<section class="page-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 col-lg-9">
        <h1 class="fw-bold mb-2">Manage Tickets</h1>
        <p class="mb-0">View, edit, and manage all the tickets for your event</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}" ?>" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-arrow-left me-2"></i> Event Details
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Main Content -->
<section class="content-section py-4">
  <div class="container">
    <div class="content-container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="section-title mb-0"><?= $event['name'] ?></h3>
        <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/ticket/create" ?>" class="btn btn-primary-action">
          <i class="fas fa-plus me-2"></i> Add New Ticket
        </a>
      </div>

      <!-- Tickets List -->
      <div class="tickets-list">
      <?php if($tickets): ?>
        <!-- Ticket -->
      <?php foreach($tickets as $ticket): ?>
        <div class="ticket-card">
          <div class="ticket-header">
            <div>
              <h4 class="ticket-name"><?= $ticket['type'] ?></h4>
              <span class="detail-label ps-2 pb-3"><?= $ticket['details'] ?></span>
            </div>
            <div class="ticket-status">
              <span class="status-indicator status-active"></span>
              <span class="status-text">Active</span>
            </div>
          </div>
          <div class="ticket-details">
            <div class="detail-item">
              <span class="detail-label">Price</span>
              <span class="detail-value"><?= formatCurrency($ticket['price']) ?></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Available</span>
              <span class="detail-value"><?= $ticket['quantity'] ?></span>
            </div>
          </div>
          <div class="ticket-actions">
            <a href="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/ticket/edit/{$ticket['ticket_id']}" ?>" class="btn btn-action btn-edit">
              <i class="fas fa-edit me-2"></i> Edit
            </a>

            <form action="<?= URL_ROOT . "/organiser/events/details/{$event['event_id']}/ticket/delete/{$ticket['ticket_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone and will remove all associated data. Any users who have already purchased this ticket will still be able to access it.');">
              <button type="submit" class="btn btn-action btn-delete">
              <i class="fas fa-trash-alt me-2"></i> Delete
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
      <?php endif; ?>
      </div>

      <!-- Pagination -->
      <nav aria-label="Tickets pagination">
        <?= $pagination ?>
      </nav>
    </div>
  </div>
</section>

<?php $this->end() ?>
