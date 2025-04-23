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

use PhpStrike\app\components\CrumbsComponent;
use celionatti\Bolt\Forms\BootstrapForm;

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Update User']); ?>
<section class="section">
    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image("", "default"); ?>" alt="" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post">
                <div class="row g-3">

                    <?= BootstrapForm::inputField("First Name", "first_name", old_value("first_name", $user["first_name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Last Name", "last_name", old_value("last_name", $user["last_name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("E-Mail", "email", old_value("email", $user["email"] ?? ''), ['class' => 'form-control', 'type' => 'email'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::inputField("Phone Number", "phone", old_value("phone", $user["phone"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::selectField("Role", "role", $user["role"] ?? '', $roleOpts, ['class' => 'form-control'], ['class' => 'col-sm-6 mb-3'], $errors) ?>
                </div>

                <hr class="my-4">

                <div class="row gap-3">
                    <a href="<?= URL_ROOT . "/admin/users/manage" ?>" class="btn btn-danger btn-lg col-4">Cancel</a>
                    <button class="btn btn-dark btn-lg col" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>

</section>
<?php $this->end() ?>