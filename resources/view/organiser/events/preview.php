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
        <h1 class="fw-bold mb-2">Event Preview (<?= $event['name'] ?>)</h1>
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
      <div class="step">2</div>
      <span class="step-label">Tickets</span>
    </div>
    <div class="position-relative">
      <div class="step active">3</div>
      <span class="step-label">Publish</span>
    </div>
  </div>
</div>

<!-- Form Section -->
<section class="form-section">
  <div class="container">
    <!-- Basic Info -->
      <div class="form-container">
        <h3 class="section-title">Basic Information</h3>
        <table class="table">
          <tr>
            <th>Event Name:</th>
            <td><?= $event['name'] ?></td>
            <th>Event Tag:</th>
            <td><?= $event['tags'] ?></td>
          </tr>
          <tr>
            <th>Event Date/Time:</th>
            <td><?= TimeDateUtils::create($event['date_time'])->toCustomFormat("F j, Y - h:i:s a") ?></td>
            <th>Event Location:</th>
            <td><?= $event['location'] ?></td>
          </tr>
          <tr>
            <th>Contact Email:</th>
            <td><?= $event['mail'] ?></td>
            <th>Contact Phone:</th>
            <td><?= $event['phone'] ?></td>
          </tr>
        </table>
      </div>

    <form action="" method="post">
      <div class="form-container">
        <h3 class="section-title">Update Event Status</h3>
        <div class="row g-3">
          <?= BootstrapForm::selectField("Event Status", "status", $event["status"] ?? '', $statusOpts, ['class' => 'form-select'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::selectField("Event Ticket Sale", "ticket_sale", $event["ticket_sale"] ?? '', $saleOpts, ['class' => 'form-select'], ['class' => 'col-md-6'], $errors) ?>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="d-flex justify-content-between mt-4 mb-5">
        <a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="btn btn-outline-action">
          <i class="fas fa-times me-2"></i> Cancel
        </a>
        <div>
          <button type="submit" class="btn btn-primary-action">Save <i class="fas fa-save ms-2"></i></button>
        </div>
      </div>
    </form>
  </div>
</section>

<?php $this->end() ?>
