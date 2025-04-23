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
  input[type="radio"]:checked {
    background-color: var(--primary-green);
    border-color: var(--primary-green);
  }

  .details-card {
      border: 2px solid var(--primary-green);
      border-radius: 15px;
  }

  .payment-card {
      border: 2px solid var(--dark-blue);
      transition: all 0.3s ease;
      cursor: pointer;
  }

  .payment-card:hover {
      border-color: var(--medium-blue);
      transform: translateY(-5px);
  }

  .bank-transfer-card {
      border: 2px solid var(--dark-blue);
  }

  .bank-details {
      background: var(--primary-green);
      border-radius: 10px;
      display: none;
  }

  .copy-btn {
      cursor: pointer;
      transition: all 0.3s ease;
  }

  .copy-btn:hover {
      color: var(--primary-green);
  }
</style>
<?php $this->end() ?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<main class="py-5 mt-3">
  <div class="container">
      <div class="row g-3">
          <!-- Payment Methods -->
          <div class="col-lg-8 col-12">
              <h2 class="text-primary-green mb-4">Select Payment Method</h2>

              <!-- Paystack Card -->
              <div class="payment-card bg-white p-4 rounded-3 mb-4">
                  <div class="d-flex align-items-center gap-4 mb-4">
                      <img src="<?= get_image("/assets/img/paystack.png") ?>" alt="Paystack" style="height: 40px" loading="lazy">
                      <div>
                          <h3 class="text-primary-green mb-1">Paystack</h3>
                          <p class="text-secondary-green mb-0">Secure payments with cards, bank accounts, or mobile money</p>
                      </div>
                  </div>
                  <form action="<?= URL_ROOT . "/initialize-payment" ?>" method="post">
                    <?= BootstrapForm::hidden("email", $transaction['email']) ?>
                    <?= BootstrapForm::hidden("amount", $transaction['total_amount']) ?>
                    <?= BootstrapForm::hidden("reference", $transaction['reference_id']) ?>
                    <?= BootstrapForm::hidden("transaction_id", $transaction['transaction_id']) ?>
                    <button class="btn btn-ticket w-100" type="submit">
                      Continue with Paystack
                    </button>
                  </form>
              </div>

              <!-- Flutterwave Card -->
              <div class="payment-card bg-white p-4 rounded-3 mb-4">
                  <div class="d-flex align-items-center gap-4 mb-4">
                      <img src="<?= get_image("/assets/img/flutterwave.png") ?>" alt="Flutterwave" style="height: 40px" loading="lazy">
                      <div>
                          <h3 class="text-primary-green mb-1">Flutterwave</h3>
                          <p class="text-secondary-green mb-0">Secure payments with cards, bank accounts, or mobile money</p>
                      </div>
                  </div>
                  <form action="<?= URL_ROOT . "/initialize-flutterwave-payment" ?>" method="post">
                    <?= BootstrapForm::hidden("email", $transaction['email']) ?>
                    <?= BootstrapForm::hidden("amount", $transaction['total_amount']) ?>
                    <?= BootstrapForm::hidden("reference", $transaction['reference_id']) ?>
                    <?= BootstrapForm::hidden("transaction_id", $transaction['transaction_id']) ?>
                    <button class="btn btn-primary-green w-100 disabled" type="submit">
                      Continue with Flutterwave
                    </button>
                  </form>
              </div>

              <!-- Stripe Card -->
              <!-- <div class="payment-card bg-white p-4 rounded-3 mb-4">
                  <div class="d-flex align-items-center gap-4 mb-4">
                      <img src="stripe-logo.png" alt="Stripe" style="height: 40px">
                      <div>
                          <h3 class="text-primary-green mb-1">Stripe</h3>
                          <p class="text-secondary-green mb-0">Pay with credit/debit card</p>
                      </div>
                  </div>
                  <button class="btn btn-primary-green w-100">
                      Continue with Stripe
                  </button>
              </div> -->

              <div class="mt-4">
                  <a href="<?= URL_ROOT . "/events/tickets/{$event['event_id']}/contact?ticket_id={$transaction['ticket_id']}&quantity={$transaction['quantity']}" ?>" class="btn btn-outline-danger">Back</a>
              </div>
          </div>

          <!-- Event Sidebar -->
          <div class="col-lg-4 col-12 details-card">
              <div class="bg-white rounded-3 overflow-hidden">
                  <img src="<?= get_image($event['image'], "default") ?>" class="img-fluid" alt="Event" loading="lazy">
                  <div class="p-4">
                      <h4 class="text-primary-green"><?= $event['name'] ?></h4>
                      <div class="text-secondary-green">
                          <p class="mb-2"><i class="fas fa-calendar me-2"></i><?= TimeDateUtils::create($event['date_time'])->toCustomFormat('F j, Y') ?></p>
                          <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i><?= $event['location'] ?></p>
                      </div>
                      <hr class="my-4">
                      <div class="text-primary-green">
                          <div class="d-flex justify-content-between mb-2">
                              <span>Total:</span>
                              <strong class="border-bottom border-danger border-3"><?= formatCurrency($transaction['total_amount']) ?></strong>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>

<?php $this->start("script") ?>
<script src="https://js.paystack.co/v2/inline.js"></script>

<script type="text/javascript">
  const popup = new PaystackPop()
  popup.resumeTransaction(access_code)
</script>
<?php $this->end() ?>