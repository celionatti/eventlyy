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

<!-- About Hero Section -->
<section class="about-hero">
<div class="container">
  <div class="row align-items-center">
    <div class="col-lg-6">
      <h1 class="hero-title">Transforming Event Experiences Since <?= setting("est", "2024") ?></h1>
      <p class="hero-subtitle">Connecting people through innovative event solutions</p>
    </div>
    <div class="col-lg-6">
      <img src="<?= get_image("", "default") ?>" alt="Team collaborating" class="hero-img">
    </div>
  </div>
</div>
</section>

<!-- Our Mission -->
<section class="mission-section">
<div class="container">
  <div class="row">
    <div class="col-lg-6 order-lg-2">
      <div class="mission-image">
        <img src="<?= get_image("", "default") ?>" alt="Event planning" class="img-fluid">
      </div>
    </div>
    <div class="col-lg-6 order-lg-1">
      <div class="mission-content">
        <h2 class="section-title">Our Mission</h2>
        <p class="mission-text">At Eventlyy, we're revolutionizing the way people create, manage, and experience events. Our platform combines cutting-edge technology with human-centered design to empower:</p>
        <ul class="mission-list">
          <li><i class="fas fa-check-circle me-2"></i>Event organizers to create memorable experiences</li>
          <li><i class="fas fa-check-circle me-2"></i>Attendees to connect meaningfully</li>
          <li><i class="fas fa-check-circle me-2"></i>Businesses to achieve their engagement goals</li>
        </ul>
      </div>
    </div>
  </div>
</div>
</section>

<!-- Our Story -->
<section class="story-section bg-light">
<div class="container">
  <h2 class="section-title text-center mb-5">Our Journey</h2>
  <div class="timeline">
    <div class="timeline-item">
      <div class="timeline-date">2024</div>
      <div class="timeline-content">
        <h4>Founded in Lagos Nigeria</h4>
        <p>Started as a small team passionate about improving event experiences</p>
      </div>
    </div>
    <div class="timeline-item">
      <div class="timeline-date">2025</div>
      <div class="timeline-content">
        <h4>Launched Virtual Event Platform</h4>
        <p>Pioneered hybrid event solutions during global shifts</p>
      </div>
    </div>
    <div class="timeline-item">
      <div class="timeline-date">2025</div>
      <div class="timeline-content">
        <h4>10 Events Hosted</h4>
        <p>Reached over 10 successful events</p>
      </div>
    </div>
    <!-- <div class="timeline-item">
      <div class="timeline-date">2025</div>
      <div class="timeline-content">
        <h4>Global Expansion</h4>
        <p>Opened offices in 3 continents with 50+ team members</p>
      </div>
    </div> -->
  </div>
</div>
</section>

<?php if($teams): ?>
<!-- Team Section -->
<section class="team-section">
<div class="container">
  <h2 class="section-title text-center mb-5">Meet the Leadership</h2>
  <div class="row">
    <?php foreach($teams as $team): ?>
      <?php $socialLinks = isset($team['socials']) ? explode(',', $team['socials']) : []; ?>
    <div class="col-md-4 mb-4">
      <div class="team-card">
        <img src="<?= get_image($team['image'], "avatar") ?>" class="team-img w-100" alt="Team Member- <?= $team['name'] ?>" loading="lazy">
        <div class="team-info">
          <h5><?= $team['nickname'] ?></h5>
          <p><?= $team['role'] ?></p>
          <div class="team-social">
            <a href="<?= $socialLinks[0] ?? "#" ?>"><i class="fab fa-twitter"></i></a>
            <a href="<?= $socialLinks[1] ?? "#" ?>"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <!-- Add more team members -->
  </div>
</div>
</section>
<?php endif; ?>

<!-- Values Section -->
<section class="values-section bg-dark-blue text-white">
<div class="container">
  <h2 class="section-title text-center mb-5">Our Core Values</h2>
  <div class="row text-black">
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <i class="fas fa-users value-icon"></i>
        <h4>Community First</h4>
        <p>We prioritize meaningful connections over transactions</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <i class="fas fa-lightbulb value-icon"></i>
        <h4>Innovate Daily</h4>
        <p>Continuous improvement drives our every decision</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="value-card">
        <i class="fas fa-hand-holding-heart value-icon"></i>
        <h4>Integrity Always</h4>
        <p>We do business transparently and ethically</p>
      </div>
    </div>
  </div>
</div>
</section>

<!-- Stats Section -->
<section class="stats-section">
<div class="container">
  <div class="row text-center">
    <div class="col-md-3 mb-4">
      <div class="stat-number"><?= setting("events_hosted", "10+") ?></div>
      <div class="stat-label">Events Hosted</div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-number"><?= setting("countries_served", "1+") ?></div>
      <div class="stat-label">Countries Served</div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-number"><?= setting("customer_satisfaction", "99%") ?></div>
      <div class="stat-label">Customer Satisfaction</div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="stat-number"><?= setting("team_members", "20+") ?></div>
      <div class="stat-label">Team Members</div>
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
    <a href="<?= URL_ROOT . "/contact" ?>" class="btn btn-outline-light btn-lg">Contact Us</a>
  </div>
</div>
</section>

<?= renderComponent(FooterComponent::class); ?>

<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>