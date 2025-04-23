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


<?php $this->start('header') ?>
<!-- Include SummerNote Editor stylesheet -->
<link href="<?= asset("packages/summernote/summernote-lite.min.css") ?>" rel="stylesheet">
<?php $this->end() ?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => '<svg class="bi h3"><use xlink:href="#gear-wide-connected" /></svg> Create Setting']); ?>

<section class="section">
    <div class="d-flex justify-content-center align-items-center gap-3 border-bottom mb-2 pb-1">
        <a class="btn btn-outline-success px-4 py-1" href="<?= URL_ROOT . "/admin/settings/manage" ?>">Manage</a>
    </div>

    <div class="card bg-transparent p-0 px-3 py-1">
        <div class="card-body">
            <form action="" method="post">
                <div class="row g-3">

                    <?= BootstrapForm::inputField("Name", "name", old_value("name", $data["name"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::selectField("Status", "status", $data["status"] ?? '', $statusOpts, ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::textareaField("Content", "value", old_value("value", $data["value"] ?? ''), ['class' => 'form-control summernote'], ['class' => 'col-sm-12'], $errors) ?>
                </div>

                <hr class="my-2">

                <button class="w-100 btn btn-dark" type="submit">Create</button>
            </form>
        </div>
    </div>
</section>

<?php $this->end() ?>

<?php $this->start("script") ?>
<script src="<?= asset("packages/summernote/summernote-lite.min.js") ?>"></script>
<script>
    $('.summernote').summernote({
        placeholder: 'Setting Content',
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