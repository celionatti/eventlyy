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
use celionatti\Bolt\Forms\BootstrapForm;

use celionatti\Bolt\Illuminate\Utils\StringUtils;

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => '<i class="fa-solid fa-people-group"></i> Teams']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/settings/teams/create" ?>">Create</a>
    </div>

    <div class="card bg-transparent p-0 px-3 py-1">
        <div class="card-header bg-transparent border-bottom p-0 pb-1">
            <h6>Manage Teams</h6>
        </div>
        <div class="card-body">
            <?php if($teams): ?>
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Nickname</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($teams as $key => $team): ?>
                <tr>
                    <td><?= ($key + 1) ?></td>
                    <td><img src="<?= get_image($team['image'], "default") ?>" class="d-block" style="height:50px;width:50px;object-fit:cover;border-radius: 10px;cursor: pointer;"></td>
                    <td class="text-capitalize"><?= $team['name'] ?></td>
                    <td class="text-capitalize"><?= $team['nickname'] ?></td>
                    <td class=""><?= $team['email'] ?></td>
                    <td class="text-capitalize"><?= $team['role'] ?></td>
                    <td class="text-end">
                        <a href="<?= URL_ROOT . "/admin/settings/teams/edit/{$team['team_id']}" ?>" class="m-1 btn btn-info btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>

                        <form action="<?= URL_ROOT . "/admin/settings/teams/delete/{$team['team_id']}" ?>" method="post" class="m-1 d-inline-block" onsubmit="return confirm('Are you sure you want to delete this team member?');">
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
    </div>
</section>

<?php $this->end() ?>
