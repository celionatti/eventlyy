<?php

use PhpStrike\app\components\CrumbsComponent;

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

use celionatti\Bolt\Illuminate\Utils\TimeDateUtils;
use celionatti\Bolt\Illuminate\Utils\StringUtils;


?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Event Details']); ?>

<section class="section">

    <div class="card mb-4 mt-2">
        <div class="card-body">
            <div class="col-12">
                <h5 class="card-title">All Transactions</h5>
            </div>
        </div>
    </div>

    <div class="table-responsive small">
        <?php if($transactions): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Event</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Token</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $key => $transaction): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $transaction['event_name'] ?></td>
                    <td><?= $transaction['type'] ?></td>
                    <td><?= $transaction['quantity'] ?></td>
                    <td><?= formatCurrency($transaction['amount']) ?></td>
                    <td class="fw-medium <?= $transaction['status'] === 'pending' ? 'bg-danger' : 'bg-success' ?> text-white text-capitalize"><?= $transaction['status'] ?></td>
                    <td><?= $transaction['reference_id'] ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-danger">
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong><?= formatCurrency($transactions[0]['confirmed_total']) ?></strong></td>
                </tr>
            </tbody>
        </table>
        <nav class="d-flex justify-content-center align-items-center">
            <?= $pagination ?>
        </nav>
        <?php else: ?>
            <h5 class="text-capitalize text-body-danger text-center border-bottom border-secondary border-2 p-2 mt-3">No Data yet!</h5>
        <?php endif; ?>
    </div>
</section>

<?php $this->end() ?>