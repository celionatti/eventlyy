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
use PhpStrike\app\components\ProfileSidebarComponent;

use celionatti\Bolt\Forms\BootstrapForm;

$user = auth_user();

?>

<?php $this->start('header') ?>
<style type="text/css">
    .account-nav {
        background: var(--dark-blue);
        color: #fff !important;
        border-radius: 15px;
        padding: 1rem;
    }

    .account-nav .nav-link {
        color: var(--primary-green);
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
    }

    .account-nav .nav-link.active {
        background: var(--primary-green);
        color: white !important;
    }

    .ticket-card {
        border: 2px solid var(--light-green);
        transition: all 0.3s ease;
    }

    .ticket-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-green);
    }

    .ticket-qr {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .order-timeline {
        border-left: 3px solid var(--light-green);
        margin-left: 1rem;
        padding-left: 2rem;
    }

    .avatar-upload {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-green);
    }

    .status-badge {
        background: var(--secondary-green);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.9em;
    }

    label {
        color: var(--secondary-green);
    }
</style>
<?php $this->end() ?>

<?php $this->start('content') ?>
<?= renderComponent(NavComponent::class); ?>

<!-- Profile Section -->
<section class="py-5 mt-3">
    <div class="container">
        <div class="row g-5">
            <!-- Side Navigation -->
            <?= renderComponent(ProfileSidebarComponent::class, ['user' => $user]); ?>

            <!-- Profile Content -->
            <div class="col-lg-9">
                <div class="bg-white rounded-3 p-4">
                    <h2 class="text-primary-green mb-4">Change Password</h2>

                    <!-- Change Password -->
                    <div class="mb-5">
                        <form action="" method="post">
                            <div class="row g-4">
                                <?= BootstrapForm::inputField("Current Password", "current_password", '', ['class' => 'form-control border-primary-green', 'type' => 'password', 'placeholder' => 'Your Current Password'], ['class' => 'col-sm-12 mb-1'], $errors) ?>

                                <?= BootstrapForm::inputField("New Password", "password", '', ['class' => 'form-control border-primary-green', 'type' => 'password', 'placeholder' => 'Your New Password'], ['class' => 'col-sm-12 mb-1'], $errors) ?>

                                <?= BootstrapForm::inputField("Confirm Password", "password_confirm", '', ['class' => 'form-control border-primary-green', 'type' => 'password', 'placeholder' => 'Confirm Password'], ['class' => 'col-sm-12 mb-1'], $errors) ?>

                                <div class="col-12 text-end">
                                    <button class="btn btn-warning" type="submit">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>