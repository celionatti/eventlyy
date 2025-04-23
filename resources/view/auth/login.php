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
use PhpStrike\app\components\LogoComponent;

?>

<?php $this->start('content') ?>
<div class="container-fluid p-0">
        <div class="row g-0 split-container">
            <!-- Brand side (left on desktop, top on mobile) -->
            <div class="col-md-5 brand-side">
                <h1 class="logo"><a href="<?= URL_ROOT ?>">eventlyy</a></h1>
                <p class="tagline">Your one-stop destination for all event tickets</p>
                <div class="d-none d-md-block">
                    <img src="<?= get_image("", "default") ?>" alt="Eventlyy illustration" class="brand-image">
                </div>
            </div>

            <!-- Login form side (right on desktop, bottom on mobile) -->
            <div class="col-md-7 login-side">
                <div class="login-form-container">
                    <h2 class="login-title">Sign in to your account</h2>

                    <form action="" method="post">
                        <?= BootstrapForm::inputField("Email address", "email", old_value("email", $auth["email"] ?? ''), ['class' => 'form-control', 'type' => 'email', 'placeholder' => 'Enter your email'], ['class' => 'mb-3'], $errors) ?>

                        <?= BootstrapForm::inputField("Password", "password", old_value("password", $auth["password"] ?? ''), ['class' => 'form-control', 'type' => 'password', 'placeholder' => 'Enter your password'], ['class' => 'mb-3'], $errors) ?>

                        <div class="form-options">
                            <?= BootstrapForm::checkField("Remember Me", "remember", "", ['class' => 'form-check-input'], ['class' => 'form-check'], $errors) ?>

                            <a href="<?= URL_ROOT . "/auth/forgot-password" ?>" class="forgot-password">Forgot Password?</a>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="sign-up-text mb-0">Don't have an account? <a href="<?= URL_ROOT . "/auth/register" ?>" class="sign-up-link">Sign up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->end() ?>