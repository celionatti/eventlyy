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

use PhpStrike\app\components\CrumbsComponent;

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Manage Messages']); ?>

<section class="section">

    <div class="table-responsive small">
        <?php if($messages): ?>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Message</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $key => $message): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $message['name'] ?></td>
                        <td><?= $message['email'] ?></td>
                        <td><?= $message['message'] ?></td>
                        <td><?= $message['status'] ?></td>
                        <td class="text-end">
                            <form action="<?= URL_ROOT . "/admin/messages/delete/{$message['message_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this message?');">
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