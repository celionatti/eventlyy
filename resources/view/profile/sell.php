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
                    <h2 class="text-primary-green mb-4">Assign / Give Away Ticket</h2>

                    <!-- Personal Info -->
                    <div class="mb-5">
                      <div class="alert alert-success text-primary-green fw-medium">Please note the name assign to ticket will be the verification name at the entrance, so you can only sell by changing the name on a ticket, and please note, ticket purchased not by you is at your own risk. But incase of any fraud activities you can report. Thank you.</div>

                      <form action="" method="post">
                        <?php for ($i = 0; $i < $ticket['quantity']; $i++): ?>
                        <div class="mb-4">
                          <h6 class="text-secondary">Attendee <?= $i + 1 ?></h6>
                          <?= BootstrapForm::inputField("Fullname", "assign_to[]", old_value("assign_to[{$i}]", $assign[$i] ?? ''), ['class' => 'form-control-lg border-primary-green'], ['class' => 'col-sm-12 mb-3'], $errors) ?>
                        </div>
                        <?php endfor; ?>

                        <hr class="border-primary-green">

                        <div class="col-12 text-end">
                            <a href="<?= URL_ROOT . "/profile/{$ticket['user_id']}/tickets" ?>" class="btn btn-danger" type="submit">Back</a>
                            <button class="btn btn-primary-green" type="submit">Save Changes</button>
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