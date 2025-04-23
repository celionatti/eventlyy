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

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Manage Payouts']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/payouts/transactions" ?>">Transactions</a>
    </div>

    <div class="table-responsive small">
        <?php if($payments): ?>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Bank</th>
                    <th scope="col">Account Number</th>
                    <th scope="col">Account Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $key => $payment): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $payment['bank_name'] ?></td>
                    <td><?= $payment['account_number'] ?></td>
                    <td><?= $payment['account_name'] ?></td>
                    <td><?= formatCurrency($payment['balance']) ?></td>
                    <td class="fw-medium text-capitalize <?= $payment['status'] === "active" ? "text-success" : "text-danger" ?>"><?= $payment['status'] ?></td>
                    <td class="text-end">
                        <?php if($payment['status'] === "active"): ?>
                            <a href="<?= URL_ROOT . "/admin/payouts/transfer/{$payment['payment_id']}" ?>" class="m-1 d-inline-block" onclick="return confirm('Are you sure you have send the funds?');">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-check-circle"></i>
                                    Send
                                </button>
                            </a>
                        <?php endif; ?>
                        <?php if($payment['status'] === "disable"): ?>
                            <form action="<?= URL_ROOT . "/admin/payouts/delete/{$payment['payment_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete bank?');">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash-alt"></i>
                                Send
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
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