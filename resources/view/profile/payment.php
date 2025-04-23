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
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1rem;
    }

    .account-nav .nav-link {
        color: var(--secondary-green);
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
        <div class="row g-3">
            <!-- Side Navigation -->
            <?= renderComponent(ProfileSidebarComponent::class, ['user' => $user]); ?>

            <!-- Profile Content -->
            <div class="col-lg-9">
                <div class="bg-white rounded-3 p-4">
                    <h2 class="text-primary-green mb-4">Payment Details</h2>
                    <div class="mb-5">
                        <div class="alert alert-success text-primary-green fw-medium">Please kindly provide one bank details, for easy transaction process. you can add as many as you want, but please note, we are not responsible for where the funds is been sent to.</div>

                        <form action="<?= URL_ROOT . "/profile/{$user['user_id']}/payment-details/new" ?>" method="post">
                            <div class="row g-4">
                                <?= BootstrapForm::inputField("Bank Name", "bank_name", old_value("bank_name", $payment["bank_name"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-6'], $errors) ?>

                                <?= BootstrapForm::inputField("Account Number", "account_number", old_value("account_number", $payment["account_number"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-6'], $errors) ?>

                                <?= BootstrapForm::inputField("Account Name", "account_name", old_value("account_name", $payment["account_name"] ?? ''), ['class' => 'form-control border-primary-green'], ['class' => 'col-sm-12'], $errors) ?>

                                <hr class="my-2">

                                <div class="col-12 text-end">
                                    <button class="btn btn-primary-green" type="submit">Add Bank</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bank Details -->
                    <div class="mb-5">
                        <div>
                            <?php if($payments): ?>
                            <table class="table table-sm table-success">
                                <thead>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Account Name</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php foreach($payments as $key => $payment): ?>
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $payment['bank_name'] ?></td>
                                        <td><?= $payment['account_number'] ?></td>
                                        <td><?= $payment['account_name'] ?></td>
                                        <td>
                                        <?php if($payment['status'] === "disable"): ?>
                                            <form action="<?= URL_ROOT . "/profile/{$payment['payment_id']}/payment-details/status/active" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to activate this bank?');">
                                                <button type="submit" class="btn btn-primary-green btn-sm">
                                                    <i class="fa-solid fa-check-circle"></i>
                                                    Activate
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?= URL_ROOT . "/profile/{$payment['payment_id']}/payment-details/status/disable" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this bank?');">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash-alt"></i>
                                                Delete
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= renderComponent(FooterComponent::class); ?>
<?php $this->end() ?>