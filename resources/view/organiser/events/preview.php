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
        <h1 class="fw-bold mb-2">Create New Event</h1>
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
      <div class="step active">1</div>
      <span class="step-label">Event Details</span>
    </div>
    <div class="position-relative">
      <div class="step">2</div>
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
    <form action="" method="post" enctype="multipart/form-data">
      <!-- Basic Info -->
      <div class="form-container">
        <h3 class="section-title">Basic Information</h3>
        <div class="row g-3">
          <?= BootstrapForm::inputField("Event Name*", "name", old_value("name", $event["name"] ?? ''), ['class' => 'form-control', 'placeholder' => 'Give your event a clear, descriptive name'], ['class' => 'col-12'], $errors) ?>

          <?= BootstrapForm::selectField("Event Category*", "category", $event["category"] ?? '', $categoryOpts, ['class' => 'form-select'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Event Tags*", "tags", old_value("tags", $event["tags"] ?? ''), ['class' => 'form-control'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::textareaField("Event Description*", "description", old_value("description", $event["description"] ?? ''), ['class' => 'form-control summernote', 'placeholder' => 'Describe your event, include details that will help attendees understand what to expect', 'rows' => '5'], ['class' => 'col-12'], $errors) ?>
        </div>
      </div>

      <!-- Time & Location -->
      <div class="form-container">
        <h3 class="section-title">Time & Location</h3>
        <div class="row g-3">
          <?= BootstrapForm::inputField("Start Date & Time*", "date_time", old_value("date_time", $event["date_time"] ?? ''), ['class' => 'form-control', 'type' => 'datetime-local'], ['class' => 'col-12'], $errors) ?>

          <?= BootstrapForm::inputField("Venue Location*", "location", old_value("location", $event["location"] ?? ''), ['class' => 'form-control', 'placeholder' => 'Enter venue name'], ['class' => 'col-12 mt-3'], $errors) ?>

        </div>
      </div>

      <!-- Event Image -->
      <div class="form-container">
        <h3 class="section-title">Event Image</h3>
        <p class="text-muted-custom mb-4">Upload a compelling image that represents your event. Recommended size: 1920 x 1080px (16:9 ratio).</p>

        <div class="upload-area" onclick="document.getElementById('imageUpload').click()">
          <input type="file" id="imageUpload" name="image" accept="image/*" style="display: none;">
          <i class="fas fa-cloud-upload-alt upload-icon"></i>
          <p class="mb-2">Drag & drop your image here or click to browse</p>
          <p class="text-muted-custom">Supports: JPG, PNG, GIF (max 10MB)</p>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="form-container">
        <h3 class="section-title">Contact Information</h3>
        <div class="row g-3">
          <?= BootstrapForm::inputField("Contact Email*", "mail", old_value("mail", $event["mail"] ?? ''), ['class' => 'form-control', 'placeholder' => 'Public contact email', 'type' => 'email'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Contact Phone*", "phone", old_value("phone", $event["phone"] ?? ''), ['class' => 'form-control', 'type' => 'tel', 'placeholder' => 'Phone number (optional)'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Social Twitter (X)", "socials[]", old_value("socials[]", $event["twitter"] ?? ''), ['class' => 'form-control', 'type' => 'url', 'placeholder' => 'Twitter / X link'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Social Facebook", "socials[]", old_value("socials[]", $event["facebook"] ?? ''), ['class' => 'form-control', 'type' => 'url', 'placeholder' => 'Facebook link'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Social Instagram", "socials[]", old_value("socials[]", $event["instagram"] ?? ''), ['class' => 'form-control', 'type' => 'url', 'placeholder' => 'Instagram link'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::inputField("Social Tik Tok", "socials[]", old_value("socials[]", $event["tiktok"] ?? ''), ['class' => 'form-control', 'type' => 'url', 'placeholder' => 'Tik Tok link'], ['class' => 'col-md-6'], $errors) ?>

        </div>
      </div>

      <div class="form-container">
        <h3 class="section-title">Extras</h3>
        <div class="row g-3">
          <?= BootstrapForm::selectField("Highlight Event", "is_highlighted", $event["is_highlighted"] ?? '', $highlightedOpts, ['class' => 'form-select'], ['class' => 'col-md-6'], $errors) ?>

          <?= BootstrapForm::selectField("Event Status", "status", $event["status"] ?? '', $statusOpts, ['class' => 'form-select'], ['class' => 'col-md-6'], $errors) ?>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="d-flex justify-content-between mt-4 mb-5">
        <a href="<?= URL_ROOT . "/organiser/events/manage" ?>" class="btn btn-outline-action">
          <i class="fas fa-times me-2"></i> Cancel
        </a>
        <div>
          <button type="submit" class="btn btn-primary-action">Continue to Tickets <i class="fas fa-arrow-right ms-2"></i></button>
        </div>
      </div>
    </form>
  </div>
</section>

<?php $this->end() ?>
