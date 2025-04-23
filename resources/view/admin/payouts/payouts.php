<?php

use PhpStrike\app\components\CrumbsComponent;

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

use celionatti\Bolt\Forms\BootstrapForm;

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Transfer Funds']); ?>

<section class="section">
    <div class="row g-3">
        <div class="col-md-5 col-lg-4 order-md-last">
        <img src="<?= get_image("", "default"); ?>" alt="" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
    </div>
    <div class="col-md-7 col-lg-8">
        <form action="" method="post">
            <div class="row g-3">
                <?= BootstrapForm::hidden("account_number", $payment["account_number"]) ?>
                <?= BootstrapForm::hidden("to_user", $payment["user_id"]) ?>
                <?= BootstrapForm::inputField("Account Number", "account_number", old_value("account_number", $payment["account_number"] ?? ''), ['class' => 'form-control', 'disabled' => 'disabled'], ['class' => 'col-sm-6'], $errors) ?>

                <?= BootstrapForm::inputField("Account Name", "account_name", old_value("account_name", $payment["account_name"] ?? ''), ['class' => 'form-control', 'disabled' => 'disabled'], ['class' => 'col-sm-6'], $errors) ?>

                <?= BootstrapForm::inputField("Amount", "amount", old_value("amount", $payment["balance"] ?? ''), ['class' => 'form-control', 'type' => 'number'], ['class' => 'col-sm-6'], $errors) ?>

                <?= BootstrapForm::inputField("Reference ID", "reference_id", old_value("reference_id", $payment["reference_id"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                <?= BootstrapForm::selectField("Transfer Status", "status", $payment["status"] ?? '', $statusOpts, ['class' => 'form-control'], ['class' => 'col-sm-12 mb-3'], $errors) ?>

            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-dark btn-lg" type="submit">Transfer</button>
        </form>
    </div>
    </div>
</section>

<?php $this->end() ?>