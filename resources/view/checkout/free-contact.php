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

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>

<?php $this->start('header') ?>
<style type="text/css">
  .checkout-card {
      border: 2px solid var(--primary-green);
      border-radius: 15px;
  }

  .summary-card {
      background: rgba(255, 255, 255, 0.9);
      border: 2px solid var(--medium-blue);
      border-radius: 15px;
  }

  .form-control:focus {
      border-color: var(--primary-green);
      box-shadow: 0 0 0 0.25rem rgba(26, 93, 26, 0.25);
  }

  .payment-icon {
      width: 50px;
      height: 30px;
      object-fit: contain;
  }
</style>
<?php $this->end() ?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<main class="py-5 mt-3">
  <div class="container">
      <div class="row g-3">
          <!-- Checkout Form -->
          <div class="col-lg-8">
              <div class="checkout-card bg-white p-4">
                  <h2 class="text-primary-green mb-4">Ticket Details</h2>

                  <form action="" method="post">
                    <?= BootstrapForm::hidden("ticket_id", $ticket['ticket_id']) ?>
                    <?php for ($i = 0; $i < $quantity; $i++): ?>
                    <div class="mb-4">
                        <h4 class="text-primary-green mb-1">Attendee <?= $i + 1 ?></h4>
                        <div class="row g-3">
                          <?= BootstrapForm::inputField("Fullname", "assign_to[]", old_value("assign_to.{$i}", $contact["assign_to"][$i] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-12 mb-3'], $errors) ?>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <div class="mt-3">
                        <div class="d-flex gap-3 mt-4">
                            <button class="btn btn-buy" type="submit">
                                <i class="fas fa-lock me-2"></i>Continue
                            </button>
                            <a href="<?= URL_ROOT . "/events/view/{$event['event_id']}" ?>" class="btn btn-outline-danger">Back to Event</a>
                        </div>
                    </div>
                  </form>
              </div>
          </div>

          <!-- Order Summary -->
          <div class="col-lg-4">
              <div class="summary-card p-4">
                  <h3 class="text-primary-green mb-4">Order Summary</h3>

                  <!-- Ticket Details -->
                  <div class="mb-4">
                      <div class="d-flex justify-content-between mb-2">
                          <span class="text-secondary-green"><?= $ticket['type'] ?> x<?= $quantity ?></span>
                          <span class="text-primary-green"><?= formatCurrency($overall['amount']) ?></span>
                      </div>
                      <div class="d-flex justify-content-between mb-2">
                          <span class="text-secondary-green">Service Fee</span>
                          <span class="text-primary-green"><?= formatCurrency($overall['totalFee']) ?></span>
                      </div>
                      <hr>
                      <div class="d-flex justify-content-between">
                          <strong class="text-primary-green">Total</strong>
                          <strong class="text-primary-green"><?= formatCurrency($overall['finalAmount']) ?></strong>
                      </div>
                  </div>

                  <!-- Event Info -->
                  <div class="border-top pt-4">
                      <h5 class="text-primary-green mb-3">Event Details</h5>
                      <div class="text-secondary-green">
                          <p class="mb-2"><i class="fas fa-info me-2"></i><?= $event['name'] ?></p>
                          <p class="mb-2"><i class="fas fa-calendar me-2"></i><?= TimeDateUtils::create($event['date_time'])->toCustomFormat('F j, Y | h:i A') ?></p>
                          <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i><?= $event['location'] ?></p>
                      </div>
                  </div>

              </div>
          </div>
      </div>
  </div>
</main>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>