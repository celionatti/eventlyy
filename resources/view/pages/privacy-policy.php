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
    .terms-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        border: 2px solid var(--light-green);
    }

    .terms-nav {
        position: sticky;
        top: 20px;
    }

    .terms-content h2 {
        color: var(--primary-green);
        border-bottom: 2px solid var(--light-green);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .terms-content h3 {
        color: var(--secondary-green);
        margin-top: 2rem;
    }

    .highlight-box {
        background: var(--light-green);
        border-left: 4px solid var(--primary-green);
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .text-lead > p {
        display: inline-block;
    }
</style>
<?php $this->end() ?>

<!-- The Main content is Render here. -->
<?php $this->start('content') ?>

<?= renderComponent(NavComponent::class); ?>

<main class="py-5 mt-4">
    <div class="container">
        <div class="row g-5">
            <!-- Content -->
            <div class="col-lg-8">
                <div class="terms-section p-4 p-md-5">
                    <div class="terms-content">
                        <h1 class="text-primary-green mb-4">Privacy Policy</h1>
                        <p class="text-secondary-green"><em>Last Updated: 17-FEB-2025</em></p>

                        <div class="highlight-box">
                            <p class="text-primary-green mb-0">By using our services, you agree to these terms. Please read them carefully.</p>
                        </div>

                        <h2 id="information">1. Information Collected</h2>
                        <ul class="text-secondary-green">
                            <li>Personal Data: Name, email, phone number, payment details.</li>
                            <li>Non-Personal Data: Browser type, IP address, cookies.</li>
                        </ul>

                        <h2 id="use">2. Use of Information</h2>
                        <ul class="text-secondary-green">
                            <li>Process transactions and communicate with Users.</li>
                            <li>Improve Website functionality and user experience.</li>
                            <li>Send promotional emails (opt-out available).</li>
                        </ul>

                        <h2 id="data">3. Data Sharing</h2>
                        <ul class="text-secondary-green">
                            <li>Shared with Event Organizers to fulfill ticket purchases.</li>
                            <li>Third-party payment processors (e.g., Paystack) for transactions.</li>
                            <li>Law enforcement if required by law.</li>
                        </ul>

                        <h2 id="security">4. Data Security</h2>
                        <ul class="text-secondary-green">
                            <li>SSL encryption for data transmission.</li>
                            <li>Regular security audits.</li>
                        </ul>

                        <h2 id="rights">5. User Rights</h2>
                        <ul class="text-secondary-green">
                            <li>Access, correct, or delete personal data via account settings.</li>
                            <li>Withdraw consent for marketing communications.</li>
                        </ul>

                        <h2 id="cookies">6. Cookies</h2>
                        <ul class="text-secondary-green">
                            <li>Cookies enhance user experience. Disable via browser settings.</li>
                        </ul>

                        <h2 id="privacy">7. Children’s Privacy</h2>
                        <ul class="text-secondary-green">
                            <li>Services not intended for users under 13.</li>
                        </ul>

                        <h2 id="policy">8. Changes to Policy</h2>
                        <ul class="text-secondary-green">
                            <li>Updates posted on the Website.</li>
                        </ul>

                        <div class="highlight-box mt-5">
                            <h3 class="text-primary-green">Contact Us</h3>
                            <div class="text-lead text-secondary-green mb-0">
                                For questions about these policies:<br>
                                Email: <?= setting("mail", "support@eventlyy.com.ng") ?><br>
                                Address: <?= setting("address", "378 Lagos Abeokuta Expressway Abule Egba, Lagos") ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Nav -->
            <div class="col-lg-4">
                <div class="terms-nav">
                    <div class="terms-section p-4">
                        <h4 class="text-primary-green mb-3">Quick Links</h4>
                        <div class="nav flex-column">
                            <a class="nav-link text-secondary-green" href="#information">1. Information Collected</a>
                            <a class="nav-link text-secondary-green" href="#use">2. Use of Information</a>
                            <a class="nav-link text-secondary-green" href="#data">3. Data Sharing</a>
                            <a class="nav-link text-secondary-green" href="#security">4. Data Security</a>
                            <a class="nav-link text-secondary-green" href="#rights">5. User Rights</a>
                            <a class="nav-link text-secondary-green" href="#cookies">6. Cookies</a>
                            <a class="nav-link text-secondary-green" href="#privacy">7. Children’s Privacy</a>
                            <a class="nav-link text-secondary-green" href="#policy">8. Changes to Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= renderComponent(FooterComponent::class); ?>

<?php $this->end() ?>

<!-- For Including JS function -->
<?php $this->start('script') ?>

<?php $this->end() ?>