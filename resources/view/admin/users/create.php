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

use celionatti\Bolt\Forms\BootstrapForm;

use PhpStrike\app\components\CrumbsComponent;

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Create User']); ?>
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

                    <?= BootstrapForm::inputField("Password", "password", old_value("password", $user["password"] ?? ''), ['class' => 'form-control', 'type' => 'password'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Confirm Password", "password_confirm", old_value("password_confirm", $user["password_confirm"] ?? ''), ['class' => 'form-control', 'type' => 'password'], ['class' => 'col-sm-6'], $errors) ?>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-dark btn-lg" type="submit">Create</button>
            </form>
        </div>
    </div>

</section>
<?php $this->end() ?>
