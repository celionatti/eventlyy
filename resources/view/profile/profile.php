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
                    <h2 class="text-primary-green mb-4">Personal Information</h2>

                    <!-- Personal Info -->
                    <div class="mb-5">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row g-4">
                                <?= BootstrapForm::inputField("First Name", "first_name", old_value("first_name", $user["first_name"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-6'], $errors) ?>

                                <?= BootstrapForm::inputField("Last Name", "last_name", old_value("last_name", $user["last_name"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-6'], $errors) ?>

                                <?= BootstrapForm::inputField("E-Mail", "email", old_value("email", $user["email"] ?? ''), ['class' => 'form-control border-primary-green', 'type' => 'email'], ['class' => 'col-sm-7'], $errors) ?>

                                <?= BootstrapForm::inputField("Phone Number", "phone", old_value("phone", $user["phone"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-5'], $errors) ?>

                                <hr class="border-primary-green">

                                <?php if($user['role'] !== "user"): ?>
                                <?= BootstrapForm::inputField("Business Name", "business_name", old_value("business_name", $user["business_name"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-7'], $errors) ?>
                                <?php endif; ?>
                                <?= BootstrapForm::selectField("Country", "country", $user["country"] ?? '', $countriesOpts, ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-5'], $errors) ?>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary" type="submit">Save Changes</button>
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