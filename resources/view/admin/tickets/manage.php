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

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Manage Tickets']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/tickets/create" ?>">Create</a>
        <a class="btn btn-outline-secondary px-4 py-1 disabled" aria-disabled="true">Menu</a>
    </div>

    <div class="table-responsive small">
        <?php if($tickets): ?>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Event</th>
                    <th scope="col">Ticket Type</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $key => $ticket): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $ticket['event_name'] ?></td>
                    <td><?= $ticket['type'] ?></td>
                    <td><?= $ticket['price'] ?? "0.0" ?></td>
                    <td><?= $ticket['quantity'] ?></td>
                    <td class="text-end">
                        <a href="<?= URL_ROOT . "/admin/tickets/edit/{$ticket['ticket_id']}" ?>" class="m-1 btn btn-info btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>

                        <form action="<?= URL_ROOT . "/admin/tickets/delete/{$ticket['ticket_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash-alt"></i>
                                Trash
                            </button>
                        </form>
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