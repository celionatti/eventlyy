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
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-primary btn-sm px-4" href="<?= URL_ROOT . "/admin/events/manage" ?>">Manage</a>
        <a class="btn btn-outline-success btn-sm px-4" href="<?= URL_ROOT . "/admin/events/create?ut=file" ?>">Create</a>
    </div>

    <div class="card mb-4 mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <h5 class="card-title"><?= $event['name'] ?></h5>
                    <p class="card-text">
                        <?= StringUtils::create(htmlspecialchars_decode(nl2br($event['description'])))->excerpt(100) ?>
                    </p>
                    <p class="text-primary fw-medium"><i class="fa-solid fa-location"></i> <?= $event['location'] ?></p>
                    <p class="text-primary fw-medium"><i class="fa-solid fa-calendar-alt"></i> <?= TimeDateUtils::create($event['date_time'])->toCustomFormat('F j, Y | h:i A') ?></p>
                    <p class="text-danger fw-medium">Total: <span class="border-bottom border-danger border-3"><?= formatCurrency($total['total_amount']) ?></span></p>
                    <a href="<?= URL_ROOT . "/admin/events/details/{$event['event_id']}/print" ?>" class="btn btn-warning">Print Ticket Lists</a>
                </div>
                <div class="col-md-5">
                    <img src="<?= get_image($event['image']) ?>" class="d-block" style="height:220px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive small">
        <?php if($transactions): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Ticket Price</th>
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
                    <td><?= $transaction['type'] ?></td>
                    <td><?= formatCurrency($transaction['price']) ?></td>
                    <td><?= $transaction['quantity'] ?></td>
                    <td><?= formatCurrency($transaction['amount']) ?></td>
                    <td><?= $transaction['status'] ?></td>
                    <td><?= $transaction['token'] ?></td>
                </tr>
                <?php endforeach; ?>
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