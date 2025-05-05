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

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;
use celionatti\Bolt\Forms\BootstrapForm;


?>


<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<section class="events-header">
    <div class="container text-center">
      <h1 class="display-5 fw-bold text-white mb-4">Find Ticket</h1>
      <p class="lead text-white mb-5">Type in the Ticket Number to find your ticket.</p>

      <!-- Search Bar -->
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 px-4">
          <form action="" method="post" class="search-container">
            <div class="input-group">
              <input type="text" class="form-control search-input" name="token" placeholder="Search for ticket...">
              <button class="btn search-btn" type="submit">
                <i class="fas fa-search me-1"></i> <span class="btn-text">Find Ticket</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>