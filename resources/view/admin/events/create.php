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

<?php $this->start('header') ?>
<!-- Include SummerNote Editor stylesheet -->
<link href="<?= asset("packages/summernote/summernote-lite.min.css") ?>" rel="stylesheet">
<?php $this->end() ?>

<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Create Event']); ?>
<section class="section">

    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-primary btn-sm px-4" href="<?= URL_ROOT . "/admin/events/manage" ?>">Manage</a>
    </div>

    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image($event["image"]); ?>" alt="<?= $event["name"] ?? "" ?>" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="d-flex align-items-center">
                        <small class="text-start px-1">Switch Upload: </small>
                        <?php if ($upload_type === "file") : ?>
                            <a href="<?= URL_ROOT . "/admin/events/create?ut=link" ?>" class="text-black my-2"> <i class="fa-solid fa-link"></i> Link Upload</a>
                        <?php else : ?>
                            <a href="<?= URL_ROOT . "/admin/events/create?ut=file" ?>" class="text-warning my-2"> <i class="fa-solid fa-file-import"></i> File Upload</a>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($upload_type === "file") : ?>
                            <?= BootstrapForm::fileField("Image", "image", ['class' => 'form-control', 'onchange' => "preview_image(this.files[0])", 'accept' => "image/*"], ['class' => 'col-12'], $errors) ?>
                        <?php else : ?>
                            <?= BootstrapForm::inputField("Image", "image", old_value("image", $event["image"] ?? ''), ['class' => 'form-control'], ['class' => 'col-12'], $errors) ?>
                        <?php endif; ?>
                    </div>

                    <hr class="my-1">

                    <?= BootstrapForm::inputField("Event Name", "name", old_value("name", $event["name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::inputField("Event Tags", "tags", old_value("tags", $event["tags"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::selectField("Event Category", "category", $event["category"] ?? '', $categoryOpts, ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::textareaField("Event Description", "description", old_value("description", $event["description"] ?? ''), ['class' => 'form-control summernote'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::inputField("Location", "location", old_value("location", $event["location"] ?? ''), ['class' => 'form-control'], ['class' => 'col-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Event Date", "date_time", old_value("date_time", $event["date_time"] ?? ''), ['class' => 'form-control', 'type' => 'datetime-local'], ['class' => 'col-6'], $errors) ?>

                    <small class="text-dark border-bottom border-primary py-2">Contact Info</small>

                    <?= BootstrapForm::inputField("Phone", "phone", old_value("phone", $event["phone"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Mail", "mail", old_value("mail", $event["mail"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Social Twitter (X)", "socials[]", old_value("socials[]", $event["twitter"] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Social Facebook", "socials[]", old_value("socials[]", $event["facebook"] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Social Instagram", "socials[]", old_value("socials[]", $event["instagram"] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Social Tik Tok", "socials[]", old_value("socials[]", $event["tiktok"] ?? ''), ['class' => 'form-control', 'type' => 'url'], ['class' => 'col-sm-6'], $errors) ?>

                    <?php if($is_highlighted['count'] < 3): ?>
                    <hr class="mt-4 border border-danger">

                    <?= BootstrapForm::selectField("Highlighted Event", "is_highlighted", $event["is_highlighted"] ?? '', $highlightedOpts, ['class' => 'form-control'], ['class' => 'col-sm-12 mb-3'], $errors) ?>
                    <?php endif; ?>

                    <?= BootstrapForm::selectField("Event Status", "status", $event["status"] ?? '', $statusOpts, ['class' => 'form-control'], ['class' => 'col-sm-12 mb-3'], $errors) ?>

                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-dark btn-lg" type="submit">Create Event</button>
            </form>
        </div>
    </div>

</section>
<?php $this->end() ?>

<?php $this->start("script") ?>
<script src="<?= asset("packages/summernote/summernote-lite.min.js") ?>"></script>
<script>
    function preview_image(file) {
        document.querySelector(".preview").src = URL.createObjectURL(file);
    }

    $('.summernote').summernote({
        placeholder: 'Event Description',
        tabsize: 2,
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear', 'fontname', 'fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph', 'height']],
        ],
        spellCheck: true,
    });
</script>
<?php $this->end() ?>