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

?>

<?php $this->start("header") ?>
<style type="text/css">
    .guide-step-number {
    width: 50px;
    height: 50px;
    border: 3px solid var(--primary-green);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-green);
    margin-bottom: 1rem;
    }

    .feature-card {
    transition: all 0.3s ease;
    border: 2px solid var(--light-green);
    background: rgba(255, 255, 255, 0.9);
    }

    .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    border-color: var(--primary-green);
    }

    .guide-accordion .accordion-button {
    background-color: var(--light-green);
    color: var(--primary-green);
    font-weight: 500;
    }

    .guide-accordion .accordion-button:not(.collapsed) {
    background-color: var(--primary-green);
    color: white;
    }

    .nav-highlight {
    position: relative;
    padding-left: 1.5rem;
    }

    .nav-highlight::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background-color: var(--accent-green);
    border-radius: 50%;
    }
</style>
<?php $this->end() ?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(NavComponent::class); ?>

<!-- Hero Section -->
<section class="bg-primary-green text-light-green py-5 my-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL_ROOT ?>" class="text-light-green">Home</a></li>
                <li class="breadcrumb-item active text-light-green" aria-current="page">User Guide</li>
            </ol>
        </nav>
        <h1 class="display-4 mb-4"><?= setting("company", "Eventlyy") ?> User Guide</h1>
        <p class="lead">Learn how to navigate and make the most of our ticket platform</p>
    </div>
</section>

<!-- Getting Started Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-primary-green mb-5 text-center section-header">Getting Started</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="guide-step-number">1</div>
                    <h3 class="h4 text-primary-green">Search Events</h3>
                    <p class="text-secondary-green">Use our search bar or browse categories to find events</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="guide-step-number">2</div>
                    <h3 class="h4 text-primary-green">Select Tickets</h3>
                    <p class="text-secondary-green">Choose your preferred seats and quantity</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="guide-step-number">3</div>
                    <h3 class="h4 text-primary-green">Checkout</h3>
                    <p class="text-secondary-green">Securely complete your purchase</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation Highlights -->
<section class="py-5 bg-secondary-green text-light-green">
    <div class="container">
        <h2 class="text-center mb-5">Key Features Overview</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4 rounded-3 h-100">
                    <i class="fas fa-search fa-2x text-primary-green mb-3"></i>
                    <h4 class="text-primary-green">Smart Search</h4>
                    <p class="text-secondary-green">Filter events by date, location, or price range</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4 rounded-3 h-100">
                    <i class="fas fa-mobile-alt fa-2x text-primary-green mb-3"></i>
                    <h4 class="text-primary-green">Mobile-Friendly</h4>
                    <p class="text-secondary-green">Access from any device with responsive design</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4 rounded-3 h-100">
                    <i class="fas fa-bell fa-2x text-primary-green mb-3"></i>
                    <h4 class="text-primary-green">Alerts</h4>
                    <p class="text-secondary-green">Get notifications for favorite events</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card p-4 rounded-3 h-100">
                    <i class="fas fa-lock fa-2x text-primary-green mb-3"></i>
                    <h4 class="text-primary-green">Secure Payments</h4>
                    <p class="text-secondary-green">Multiple secure payment options</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Guide Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-primary-green mb-5 text-center section-header">Detailed Guide</h2>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="guide-accordion accordion" id="guideAccordion">
                    <div class="accordion-item my-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                Creating an Account
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#guideAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    <li class="nav-highlight mb-2">Click 'Sign Up' in top navigation</li>
                                    <li class="nav-highlight mb-2">Provide required information</li>
                                    <li class="nav-highlight mb-2">Verify your email address</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item my-2">
                        <h3 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                Select an Event
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                            <div class="accordion-body">
                                <ul class="list-unstyled">
                                    <li class="nav-highlight mb-2">Click 'Sign Up' in top navigation</li>
                                    <li class="nav-highlight mb-2">Provide required information</li>
                                    <li class="nav-highlight mb-2">Verify your email address</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Add more accordion items similarly -->
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white p-4 rounded-3 shadow">
                    <h3 class="text-primary-green mb-4">Quick Tips</h3>
                    <!-- <div class="d-flex gap-3 mb-4">
                        <div class="text-center">
                            <div class="bg-primary-green text-light-green rounded-circle p-3 mb-2">
                                <i class="fas fa-filter fa-2x"></i>
                            </div>
                            <small>Use filters to narrow search</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-primary-green text-light-green rounded-circle p-3 mb-2">
                                <i class="fas fa-heart fa-2x"></i>
                            </div>
                            <small>Save favorite events</small>
                        </div>
                    </div> -->
                    <img src="<?= get_image("", "default") ?>" alt="Guide Preview" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>
</section>

<?= renderComponent(FooterComponent::class); ?>

<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>