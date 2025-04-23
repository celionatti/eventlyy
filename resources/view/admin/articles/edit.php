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
<?= renderComponent(CrumbsComponent::class, ['name' => 'Edit Article']); ?>
<section class="section">

    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image($article["image"]); ?>" alt="<?= $article["name"] ?? "" ?>" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="d-flex align-items-center">
                        <small class="text-start px-1">Switch Upload: </small>
                        <?php if ($upload_type === "file") : ?>
                            <a href="<?= URL_ROOT . "/admin/articles/edit/{$article['article_id']}?ut=link" ?>" class="text-black my-2"> <i class="fa-solid fa-link"></i> Link Upload</a>
                        <?php else : ?>
                            <a href="<?= URL_ROOT . "/admin/articles/edit/{$article['article_id']}?ut=file" ?>" class="text-warning my-2"> <i class="fa-solid fa-file-import"></i> File Upload</a>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($upload_type === "file") : ?>
                            <?= BootstrapForm::fileField("Image", "image", ['class' => 'form-control', 'onchange' => "preview_image(this.files[0])", 'accept' => "image/*"], ['class' => 'col-12'], $errors) ?>
                        <?php else : ?>
                            <?= BootstrapForm::inputField("Image", "image", old_value("image", $article["image"] ?? ''), ['class' => 'form-control'], ['class' => 'col-12'], $errors) ?>
                        <?php endif; ?>
                    </div>

                    <hr class="my-1">

                    <?= BootstrapForm::inputField("Article Title", "title", old_value("title", $article["title"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::inputField("Article Contributors", "contributors", old_value("contributors", $article["contributors"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::inputField("Article Tag", "tag", old_value("tag", $article["tag"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-6'], $errors) ?>

                    <?= BootstrapForm::textareaField("Article Content", "content", old_value("content", $article["content"] ?? ''), ['class' => 'form-control summernote'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::selectField("Article Status", "status", $article["status"] ?? '', $statusOpts, ['class' => 'form-control'], ['class' => 'col-sm-12 mb-3'], $errors) ?>

                </div>

                <hr class="my-4">

                <div class="row gap-3">
                    <a href="<?= URL_ROOT . "/admin/articles/manage" ?>" class="btn btn-danger btn-lg col-4">Cancel</a>
                    <button class="btn btn-dark btn-lg col" type="submit">Update Article</button>
                </div>
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