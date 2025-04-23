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

<section class="bg-primary-green text-light-green py-5 my-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_ROOT ?>" class="text-light-green">Home</a></li>
                <li class="breadcrumb-item active text-light-green" aria-current="page">Find Ticket</li>
            </ol>
        </nav>
        <h1 class="display-4 mb-4"><?= setting("company", "Eventlyy") ?> Find Ticket</h1>
        <p class="lead">Type in the Ticket Number to find your ticket.</p>
    </div>
</section>

  <section class="py-2 mt-1">
    <div class="container py-3">
        <section class="section">
            <form action="" method="post">
                <div class="card bg-transparent p-0 px-3 py-1">
                    <div class="card-header bg-transparent border-bottom p-2 pb-1 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Find Ticket</h6>
                    </div>
                    <div class="card-body px-0">
                        <div class="row g-4">
                            <?= BootstrapForm::inputField("Ticket Token", "token", old_value("token", $ticket["token"] ?? 'EVENTLYY-'), ['class' => 'form-control text-green fw-medium'], ['class' => 'col-sm-12'], $errors) ?>
                        </div>
                        <hr class="my-4">
                        <button class="w-100 btn btn-success btn-lg" type="submit">Find</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
  </section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>