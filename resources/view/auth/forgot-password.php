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

<?php $this->start("header") ?>
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 2rem;
    }

    .login-card {
        background: white;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .brand-side {
        background: rgba(255, 255, 255, 0.95);
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .form-side {
        padding: 3rem 2.5rem;
    }

    .brand-logo {
        width: 120px;
        margin-bottom: 2rem;
    }

    .brand-text {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a5d1a;
        letter-spacing: -1px;
    }

    .brand-tagline {
        color: #4a7856;
        font-size: 1.1rem;
        text-align: center;
        margin-top: 1rem;
    }

    .form-control:focus {
        border-color: #1a5d1a;
        box-shadow: 0 0 0 3px rgba(26, 93, 26, 0.1);
    }

    .btn-primary {
        background: #1a5d1a;
        border: none;
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }

    .btn-primary:hover {
        background: #0d3d0d;
    }

    .auth-link {
        color: #1a5d1a !important;
        font-weight: 500;
        text-decoration: none;
    }

    .auth-link:hover {
        text-decoration: underline;
    }
</style>
<?php $this->end() ?>

<?php $this->start('content') ?>
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="login-card">
                    <div class="row g-0">
                        <!-- Brand Side -->
                        <div class="col-lg-5 brand-side">
                            <div class="text-center">
                                <a href="<?= URL_ROOT ?>" class="brand-text">Eventl<span class="text-dark-green">y</span></a>
                                <p class="brand-tagline">Your Gateway to Unforgettable Experiences</p>
                                <p class="brand-tagline">Reset Your Password</p>
                            </div>
                        </div>

                        <!-- Form Side -->
                        <div class="col-lg-7 form-side">
                            <h2 class="h3 mb-4 fw-bold text-center">Forgot Password?</h2>

                            <form action="" method="post">
                                <?= BootstrapForm::inputField("Email", "email", old_value("email", $auth["email"] ?? ''),
                                    ['class' => 'form-control rounded-pill', 'type' => 'email', 'placeholder' => 'Enter your email'],
                                    ['class' => 'mb-3'], $errors) ?>


                                <button class="w-100 btn btn-primary rounded-pill py-2" type="submit">
                                    Send Reset Link
                                </button>

                                <p class="text-center mt-4 mb-0">
                                    Remember your password?
                                    <a href="<?= URL_ROOT . "/auth/login" ?>" class="auth-link">
                                        Login Here
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>