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
        <h1 class="fw-bold mb-2">New Event (<?= $event['name'] ?>)</h1>
        <p class="mb-0">Fill out the form below to create and publish your event</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="far fa-calendar-alt me-2"></i> Manage Events
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Progress Indicator -->
<div class="container mt-4">
  <div class="steps-container">
    <div class="position-relative">
      <div class="step">1</div>
      <span class="step-label">Event Details</span>
    </div>
    <div class="position-relative">
      <div class="step active">2</div>
      <span class="step-label">Tickets</span>
    </div>
    <div class="position-relative">
      <div class="step">3</div>
      <span class="step-label">Publish</span>
    </div>
  </div>
</div>

<!-- Form Section -->
<section class="form-section">
  <div class="container">
    <!-- Step one -->
    <form action="" method="post">
      <!-- Ticket Types -->
        <div class="form-container">
          <h3 class="section-title">Ticket Information</h3>
          <div class="ticket-types-container">
            <!-- First Ticket Type -->
            <div class="ticket-type-card">
              <!-- <span class="remove-ticket"><i class="fas fa-times-circle"></i></span> -->
              <div class="row g-3">
                <?= BootstrapForm::inputField("Ticket Name*", "type", old_value("type", $ticket["type"] ?? ''), ['class' => 'form-control', 'placeholder' => 'e.g., General Admission, VIP'], ['class' => 'col-12'], $errors) ?>

                <?= BootstrapForm::inputField("Price *", "price", old_value("price", $ticket["price"] ?? ''), ['class' => 'form-control', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'placeholder' => '0.00'], ['class' => 'col-md-6'], $errors) ?>

                <?= BootstrapForm::inputField("Quantity Available*", "quantity", old_value("quantity", $ticket["quantity"] ?? ''), ['class' => 'form-control', 'type' => 'number', 'placeholder' => 'Enter number of tickets'], ['class' => 'col-md-6'], $errors) ?>

                <?= BootstrapForm::textareaField("Description", "details", old_value("details", $ticket["details"] ?? ''), ['class' => 'form-control', 'placeholder' => 'Describe what is included with this ticket (optional)'], ['class' => 'col-12', 'rows' => 2], $errors) ?>

              </div>
            </div>
          </div>

        </div>

      <!-- Form Actions -->
      <div class="d-flex justify-content-between mt-4 mb-5">
        <a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="btn btn-outline-action">
          <i class="fas fa-times me-2"></i> Cancel
        </a>
        <div>
          <button type="submit" class="btn btn-primary-action"><i class="fas fa-plus ms-2"></i> Add New Ticket</button>
        </div>
        <div>
          <a href="<?= URL_ROOT . "/organiser/events/create/preview/{$event['event_id']}" ?>" class="btn btn-outline-action">Continue <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
      </div>
    </form>
  </div>
</section>

<?php $this->end() ?>
