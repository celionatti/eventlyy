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
      <div class="col-md-8 col-lg-9">
        <h1 class="fw-bold mb-2">Dashboard</h1>
        <p class="mb-0">Organisers Dashboard unit</p>
      </div>
      <div class="col-md-4 col-lg-3 text-md-end mt-3 mt-md-0">
        <a href="#" class="btn" style="background-color: var(--white); color: var(--dark-blue); border-radius: 8px; font-weight: 500;">
          <i class="fas fa-question-circle me-2"></i> Get Help
        </a>
      </div>
    </div>
  </div>
</section>

<section class="form-section">
  <div class="container">
    <!-- Stats Cards -->
  <div class="row mb-4">
      <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
          <div class="card stat-card primary h-100">
              <div class="card-body">
                  <div class="d-flex justify-content-between">
                      <div>
                          <h6 class="text-muted mb-1">Total Events</h6>
                          <h3 class="mb-0">24</h3>
                      </div>
                      <div class="bg-light-custom p-3 rounded text-primary">
                          <i class="bi bi-calendar-event fs-4"></i>
                      </div>
                  </div>
                  <p class="small mt-3 mb-0">
                      <span class="text-success me-1"><i class="fas fa-arrow-up"></i> 12%</span>
                      <span class="text-muted">from last month</span>
                  </p>
              </div>
          </div>
      </div>

      <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
          <div class="card stat-card secondary h-100">
              <div class="card-body">
                  <div class="d-flex justify-content-between">
                      <div>
                          <h6 class="text-muted mb-1">Tickets Sold</h6>
                          <h3 class="mb-0">1,892</h3>
                      </div>
                      <div class="bg-light-custom p-3 rounded text-secondary">
                          <i class="fas fa-ticket-perforated fs-4"></i>
                      </div>
                  </div>
                  <p class="small mt-3 mb-0">
                      <span class="text-success me-1"><i class="fas fa-arrow-up"></i> 8%</span>
                      <span class="text-muted">from last month</span>
                  </p>
              </div>
          </div>
      </div>

      <div class="col-md-6 col-xl-3 mb-4 mb-md-0">
          <div class="card stat-card accent h-100">
              <div class="card-body">
                  <div class="d-flex justify-content-between">
                      <div>
                          <h6 class="text-muted mb-1">Revenue</h6>
                          <h3 class="mb-0">$42,580</h3>
                      </div>
                      <div class="bg-light-custom p-3 rounded" style="color: var(--accent);">
                          <i class="fas fa-cash-coin fs-4"></i>
                      </div>
                  </div>
                  <p class="small mt-3 mb-0">
                      <span class="text-success me-1"><i class="bi bi-arrow-up"></i> 17%</span>
                      <span class="text-muted">from last month</span>
                  </p>
              </div>
          </div>
      </div>

  </div>
  </div>
</section>

<?php $this->end() ?>