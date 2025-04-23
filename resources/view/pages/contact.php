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

use celionatti\Bolt\Forms\BootstrapForm;

?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(NavComponent::class); ?>

<!-- Contact Hero -->
<section class="contact-hero">
  <div class="container">
    <div class="row text-center">
      <div class="col-lg-8 mx-auto">
        <h1 class="hero-title">Get in Touch</h1>
        <p class="hero-subtitle">We're here to help you create amazing events</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Content -->
<section class="contact-content mb-3">
  <div class="container">
    <div class="row g-5">
      <!-- Contact Form -->
      <div class="col-lg-7">
        <div class="contact-card">
          <h3 class="form-title">Send Us a Message</h3>
          <form action="<?= URL_ROOT . "/send-message" ?>" method="post">
            <div class="row g-3">
              <?= BootstrapForm::inputField("Your Name", "name", old_value("name", $contact["name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-md-6'], $errors) ?>

              <?= BootstrapForm::inputField("Email Address", "email", old_value("email", $contact["email"] ?? ''), ['class' => 'form-control', 'type' => 'email'], ['class' => 'col-md-6'], $errors) ?>

              <?= BootstrapForm::textareaField("Message", "message", old_value("message", $contact["message"] ?? ''), ['class' => 'form-control', 'rows' => 5], ['class' => 'col-12'], $errors) ?>

              <div class="col-12">
                <button type="submit" class="btn submit-btn">Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="col-lg-5">
        <div class="contact-info-card">
          <h3 class="info-title">Contact Information</h3>
          <div class="contact-method">
            <div class="method-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="method-details">
              <h5>Headquarters</h5>
              <?= setting("address", "123 Ticket Street, Event City") ?>
            </div>
          </div>

          <div class="contact-method">
            <div class="method-icon">
              <i class="fas fa-phone"></i>
            </div>
            <div class="method-details">
              <h5>Phone Numbers</h5>
              <?= setting("phone", "+1 (555) 123-4567") ?>
            </div>
          </div>

          <div class="contact-method">
            <div class="method-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="method-details">
              <h5>Email Addresses</h5>
              <?= setting("support_mail", "<p>support@eventlyy.com.ng</p>") ?>
            </div>
          </div>

          <div class="contact-method">
            <div class="method-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="method-details">
              <h5>Office Hours</h5>
              <p>Monday-Friday: 9AM - 6PM WST<br>Saturday: 10AM - 2PM WST</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- CTA Section -->
<section class="cta-section bg-primary-green text-center text-white">
<div class="container">
  <h3 class="cta-title">Ready to Transform Your Events?</h3>
  <p class="cta-text mb-4">Join thousands of organizers creating unforgettable experiences</p>
  <div class="cta-buttons">
    <a href="#" class="btn btn-light btn-lg me-3">Start Today</a>
    <a href="<?= URL_ROOT . "/about" ?>" class="btn btn-outline-light btn-lg">About Us</a>
  </div>
</div>
</section>

<?= renderComponent(FooterComponent::class); ?>

<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>