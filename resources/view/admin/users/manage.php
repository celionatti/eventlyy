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
<?= renderComponent(CrumbsComponent::class, ['name' => 'Manage Users']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success btn-sm px-4" href="<?= URL_ROOT . "/admin/users/create" ?>">Create</a>
        <a class="btn btn-outline-secondary btn-sm px-4 disabled" aria-disabled="true">Menu</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">FullName</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Role</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $key => $user): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td class="text-capitalize"><?= $user['first_name'] . " " . $user['last_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['phone'] ?? "none" ?></td>
                    <td><?= $user['business_name'] ?? "none" ?></td>
                    <td><?= $user['role'] ?></td>
                    <td class="text-end">
                        <a href="<?= URL_ROOT . "/admin/users/edit/{$user['user_id']}" ?>" class="btn btn-info btn-sm m-1"><i class="fa-solid fa-edit"></i> Edit</a>

                        <form action="<?= URL_ROOT . "/admin/users/delete/{$user['user_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
    </div>
</section>

<?php $this->end() ?>