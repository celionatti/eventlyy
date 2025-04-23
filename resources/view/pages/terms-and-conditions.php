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
                        <h1 class="text-primary-green mb-4">Terms & Conditions</h1>
                        <p class="text-secondary-green"><em>Last Updated: 17-FEB-2025</em></p>

                        <div class="highlight-box">
                            <p class="text-primary-green mb-0">By using our services, you agree to these terms. Please read them carefully.</p>
                        </div>

                        <h2 id="acceptance">1. Acceptance of Terms</h2>
                        <p class="text-secondary-green">By accessing or using www.eventlyy.com.ng ("Eventlyy"), you agree to comply with and be bound by these Terms and Conditions. If you disagree, do not use the Website.</p>

                        <h2 id="definitions">2. Definitions</h2>
                        <h3>2.1 User</h3>
                        <p class="text-secondary-green">Any person accessing the Website.</p>

                        <h3>2.2 Event Organizer</h3>
                        <p class="text-secondary-green">Entities or individuals listing events on Eventlyy.</p>

                        <h3>2.3 Ticket(s)</h3>
                        <p class="text-secondary-green">Digital or physical passes sold through the Website.</p>

                        <h2 id="responsibilities">3. User Responsibilities</h2>
                        <ul class="text-secondary-green">
                            <li>Users must be at least 18 years old or have parental consent.</li>
                            <li>Provide accurate information during account creation and ticket purchases.</li>
                            <li>Do not misuse the Website (e.g., fraud, spam, hacking).</li>
                        </ul>

                        <h2 id="registration">4. Account Registration</h2>
                        <ul class="text-secondary-green">
                            <li>Users are responsible for account security.</li>
                            <li>Eventlyy reserves the right to suspend accounts for violations.</li>
                        </ul>

                        <h2 id="purchases">5. Ticket Purchases</h2>
                        <ul class="text-secondary-green">
                            <li>All sales are final unless otherwise stated.</li>
                            <li>Tickets are subject to event availability and Organizer terms.</li>
                            <li>Eventlyy is not responsible for event cancellations, rescheduling, or changes by Organizers.</li>
                        </ul>

                        <h2 id="payments">6. Payments</h2>
                        <ul class="text-secondary-green">
                            <li>Payments are processed via secure third-party gateways (e.g., Flutterwave, Paystack).</li>
                            <li>Prices are in Nigerian Naira (NGN) and inclusive of taxes unless stated otherwise.</li>
                        </ul>

                        <h2 id="cancellations">7. Cancellations & Refunds</h2>
                        <ul class="text-secondary-green">
                            <li>Refunds are subject to the Organizer’s policy.</li>
                            <li>Service fees are non-refundable.</li>
                            <li>Contact Organizers directly for refund requests.</li>
                        </ul>

                        <h2 id="intellectual">8. Intellectual Property</h2>
                        <ul class="text-secondary-green">
                            <li>Eventlyy owns all Website content (logos, text, design).</li>
                            <li>Users may not reproduce, modify, or distribute content without permission.</li>
                        </ul>

                        <h2 id="disclaimers">9. Disclaimers</h2>
                        <ul class="text-secondary-green">
                            <li>Eventlyy does not guarantee uninterrupted or error-free service.</li>
                            <li>We are not liable for User or Organizer actions.</li>
                        </ul>

                        <h2 id="limitation">10. Limitation of Liability</h2>
                        <ul class="text-secondary-green">
                            <li>Eventlyy is not liable for indirect damages (e.g., loss of profits) arising from Website use.</li>
                        </ul>

                        <h2 id="termination">11. Termination</h2>
                        <ul class="text-secondary-green">
                            <li>Eventlyy may terminate access for violations without notice.</li>
                        </ul>

                        <h2 id="law">12. Governing Law</h2>
                        <ul class="text-secondary-green">
                            <li>Governed by Nigerian law. Disputes resolved in Nigerian courts.</li>
                        </ul>

                        <h2 id="terms">13. Changes to Terms</h2>
                        <ul class="text-secondary-green">
                            <li>Terms may be updated periodically. Continued use constitutes acceptance.</li>
                        </ul>

                        <div class="highlight-box mt-5">
                            <h3 class="text-primary-green">Contact Us</h3>
                            <div class="text-lead text-secondary-green mb-0">
                                For questions about these terms:<br>
                                Email: <?= setting("mail", "legal@eventlyy.com") ?><br>
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
                            <a class="nav-link text-secondary-green" href="#acceptance">1. Acceptance</a>
                            <a class="nav-link text-secondary-green" href="#definitions">2. Definitions</a>
                            <a class="nav-link text-secondary-green" href="#responsibilities">3. User Responsibilities</a>
                            <a class="nav-link text-secondary-green" href="#registration">4. Account Registration</a>
                            <a class="nav-link text-secondary-green" href="#purchases">5. Ticket Purchases</a>
                            <a class="nav-link text-secondary-green" href="#payments">6. Payments</a>
                            <a class="nav-link text-secondary-green" href="#cancellations">7. Cancellations & Refunds</a>
                            <a class="nav-link text-secondary-green" href="#intellectual">8. Intellectual Property</a>
                            <a class="nav-link text-secondary-green" href="#disclaimers">9. Disclaimers</a>
                            <a class="nav-link text-secondary-green" href="#limitation">10. Limitation of Liability</a>
                            <a class="nav-link text-secondary-green" href="#termination">11. Termination</a>
                            <a class="nav-link text-secondary-green" href="#law">12. Governing Law</a>
                            <a class="nav-link text-secondary-green" href="#terms">13. Changes to Terms</a>
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