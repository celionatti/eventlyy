<?php
/**
 * Framework Title: PhpStrike Framework
 * Creator: Celio natti
 * version: 1.0.0
 * Year: 2023
 */

use celionatti\Bolt\Forms\BootstrapForm;
use PhpStrike\app\components\LogoComponent;

?>

<?php $this->start("header") ?>
<style>
    .btn-outline-secondary {
        border-color: var(--medium-blue);
        color: var(--medium-blue);
    }

    .btn-outline-secondary:hover {
        background-color: var(--light-blue);
        border-color: var(--dark-blue);
        color: var(--dark-blue);
    }

    .auth-link {
        color: var(--medium-blue);
        text-decoration: none;
        font-weight: 600;
    }

    .auth-link:hover {
        color: var(--dark-blue);
        text-decoration: underline;
    }

    .account-type-card {
        border: 2px solid #e0e0e0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
    }

    .account-type-card:hover {
        border-color: var(--medium-blue);
        transform: translateY(-3px);
    }

    .account-type-card.selected {
        border-color: var(--medium-blue);
        background: rgba(23, 86, 118, 0.05);
    }

    input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
</style>
<?php $this->end() ?>

<?php $this->start('content') ?>
<div class="container-fluid p-0">
    <div class="row g-0 split-container">
        <!-- Brand side (left on desktop, hidden on mobile) -->
        <div class="col-md-5 brand-side">
            <h1 class="logo"><a href="<?= URL_ROOT ?>">eventlyy</a></h1>
            <p class="tagline">Your one-stop destination for all event tickets</p>
            <div class="d-none d-md-block">
                <img src="<?= get_image("", "default") ?>" alt="Event illustration" class="brand-image">
            </div>
        </div>

        <!-- Form side (right on desktop, full on mobile) -->
        <div class="col-md-7 form-side">
            <div class="form-container">
                <?php if(!isset($_GET['type'])): ?>
                    <!-- Account Type Selection -->
                    <h2 class="form-title">Create Your Account</h2>
                    <form action="" method="get">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="account-type-card <?= old_value('type') === 'personal' ? 'selected' : '' ?>">
                                    <input type="radio" name="type" value="personal" <?= old_value('type') === 'personal' ? 'checked' : '' ?>>
                                    <div class="card-content">
                                        <i class="fa-regular fa-user fa-2x text-dark-blue mb-3"></i>
                                        <h4 class="h5 mb-2">Personal</h4>
                                        <p class="text-muted small">For individuals attending events</p>
                                    </div>
                                </label>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="account-type-card <?= old_value('type') === 'organization' ? 'selected' : '' ?>">
                                    <input type="radio" name="type" value="organization" <?= old_value('type') === 'organization' ? 'checked' : '' ?>>
                                    <div class="card-content">
                                        <i class="fa-solid fa-briefcase fa-2x text-dark-blue mb-3"></i>
                                        <h4 class="h5 mb-2">Organization</h4>
                                        <p class="text-muted small">For businesses & event organizers</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </div>

                        <p class="text-center mt-4 mb-0">
                            Already have an account?
                            <a href="<?= URL_ROOT . "/auth/login" ?>" class="auth-link">Login Here</a>
                        </p>
                    </form>

                <?php else: ?>
                    <!-- Registration Form -->
                    <h2 class="form-title">
                        <?= $_GET['type'] === 'personal' ? 'Personal Registration' : 'Organization Registration' ?>
                    </h2>

                    <form action="" method="post">
                        <div class="row g-3">
                            <?= BootstrapForm::selectField("Country", "country", $auth["country"] ?? '', $countriesOpts,
                                ['class' => 'form-control'],
                                ['class' => 'col-12 mb-2'], $errors) ?>

                            <?php if($_GET['type'] === 'organization'): ?>
                                <div class="col-12">
                                    <?= BootstrapForm::inputField("Business Name", "business_name", old_value("business_name", $auth["business_name"] ?? ''),
                                        ['class' => 'form-control', 'placeholder' => 'Enter business name'],
                                        ['class' => 'mb-2'], $errors) ?>
                                </div>
                            <?php endif; ?>

                            <div class="col-12 col-md-6">
                                <?= BootstrapForm::inputField("First Name", "first_name", old_value("first_name", $auth["first_name"] ?? ''),
                                    ['class' => 'form-control', 'placeholder' => 'Enter first name'],
                                    ['class' => 'mb-2'], $errors) ?>
                            </div>

                            <div class="col-12 col-md-6">
                                <?= BootstrapForm::inputField("Last Name", "last_name", old_value("last_name", $auth["last_name"] ?? ''),
                                    ['class' => 'form-control', 'placeholder' => 'Enter last name'],
                                    ['class' => 'mb-2'], $errors) ?>
                            </div>

                            <div class="col-12">
                                <?= BootstrapForm::inputField("Email", "email", old_value("email", $auth["email"] ?? ''),
                                    ['class' => 'form-control', 'type' => 'email', 'placeholder' => 'Enter email address'],
                                    ['class' => 'mb-2'], $errors) ?>
                            </div>

                            <div class="col-12 col-md-12">
                                <?= BootstrapForm::inputField("Password", "password", old_value("password", $auth["password"] ?? ''),
                                    ['class' => 'form-control', 'type' => 'password', 'placeholder' => 'Create password'],
                                    ['class' => 'mb-2'], $errors) ?>
                            </div>

                            <div class="col-12 col-md-12">
                                <?= BootstrapForm::inputField("Confirm Password", "password_confirm", old_value("password_confirm", $auth["password_confirm"] ?? ''),
                                    ['class' => 'form-control', 'type' => 'password', 'placeholder' => 'Confirm password'],
                                    ['class' => 'mb-2'], $errors) ?>
                            </div>

                            <div class="col-12">
                                <?= BootstrapForm::checkField("I agree to the Terms & Conditions", "terms", "",
                                    ['class' => 'form-check-input'],
                                    ['class' => 'form-check'], $errors) ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                            <a href="<?= URL_ROOT . "/auth/register" ?>" class="btn btn-outline-secondary">Back</a>
                        </div>

                        <p class="text-center mt-4 mb-0">
                            Already have an account?
                            <a href="<?= URL_ROOT . "/auth/login" ?>" class="auth-link">Login Here</a>
                        </p>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $this->end() ?>

<?php $this->start("script") ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountCards = document.querySelectorAll('.account-type-card');

        accountCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');

            card.addEventListener('click', () => {
                accountCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
            });

            if(radio.checked) {
                card.classList.add('selected');
            }
        });
    });
</script>
<?php $this->end() ?>