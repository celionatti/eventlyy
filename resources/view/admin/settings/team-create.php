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

?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => '<i class="fa-solid fa-user-plus"></i> Team Member']); ?>
<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/settings/teams" ?>">Teams</a>
    </div>

    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image($team["image"], "default"); ?>" alt="<?= $team["name"] ?? "" ?>" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row g-3">

                    <?= BootstrapForm::fileField("Image", "image", ['class' => 'form-control', 'onchange' => "preview_image(this.files[0])", 'accept' => "image/*"], ['class' => 'col-12 my-2'], $errors) ?>

                    <hr class="my-1">

                    <?= BootstrapForm::inputField("Name", "name", old_value("name", $team["name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Nickname", "nickname", old_value("nickname", $team["nickname"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Email", "email", old_value("email", $team["email"] ?? ''), ['class' => 'form-control', 'type' => 'email'], ['class' => 'col-sm-7'], $errors) ?>

                    <?= BootstrapForm::inputField("Role", "role", old_value("role", $team["role"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-5'], $errors) ?>

                    <?= BootstrapForm::inputField("Twitter (X)", "socials[]", old_value("socials[]", $links[0] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Instagram", "socials[]", old_value("socials[]", $links[1] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-dark btn-lg" type="submit">Add Memebr</button>
            </form>
        </div>
    </div>

</section>
<?php $this->end() ?>

<?php $this->start("script") ?>
<script>
    function preview_image(file) {
        document.querySelector(".preview").src = URL.createObjectURL(file);
    }
</script>
<?php $this->end() ?>