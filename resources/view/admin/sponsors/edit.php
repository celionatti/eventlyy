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
<?= renderComponent(CrumbsComponent::class, ['name' => 'Update Sponsor']); ?>
<section class="section">

    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image($sponsor["image"], "default"); ?>" alt="<?= $sponsor["name"] ?? "" ?>" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="d-flex align-items-center">
                        <small class="text-start px-1">Switch Upload: </small>
                        <?php if ($upload_type === "file") : ?>
                            <a href="<?= URL_ROOT . "/admin/sponsors/edit/{$sponsor['sponsor_id']}?ut=link" ?>" class="text-black my-2"> <i class="fa-solid fa-link"></i> Link Upload</a>
                        <?php else : ?>
                            <a href="<?= URL_ROOT . "/admin/sponsors/edit/{$sponsor['sponsor_id']}?ut=file" ?>" class="text-warning my-2"> <i class="fa-solid fa-file-import"></i> File Upload</a>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($upload_type === "file") : ?>
                            <?= BootstrapForm::fileField("Image", "image", ['class' => 'form-control', 'onchange' => "preview_image(this.files[0])", 'accept' => "image/*"], ['class' => 'col-12'], $errors) ?>
                        <?php else : ?>
                            <?= BootstrapForm::inputField("Image", "image", old_value("image", $sponsor["image"] ?? ''), ['class' => 'form-control'], ['class' => 'col-12'], $errors) ?>
                        <?php endif; ?>
                    </div>

                    <hr class="my-1">

                    <?= BootstrapForm::inputField("Sponsor Name", "name", old_value("name", $sponsor["name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-12'], $errors) ?>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-dark btn-lg" type="submit">Update Sponsor</button>
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