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
<?= renderComponent(CrumbsComponent::class, ['name' => 'Manage Sponsors']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/sponsors/create?ut=file" ?>">Create</a>
    </div>

    <div class="table-responsive small">
        <?php if($sponsors): ?>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sponsors as $key => $sponsor): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><img src="<?= get_image($sponsor['image'], "default") ?>" class="d-block" style="height:50px;width:50px;object-fit:cover;border-radius: 10px;cursor: pointer;"></td>
                    <td><?= $sponsor['name'] ?></td>
                    <td class="text-end">
                        <a href="<?= URL_ROOT . "/admin/sponsors/edit/{$sponsor['sponsor_id']}?ut=file" ?>" class="m-1 btn btn-info btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>

                        <form action="<?= URL_ROOT . "/admin/sponsors/delete/{$sponsor['sponsor_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this sponsor?');">
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