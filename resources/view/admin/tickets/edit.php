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

use celionatti\Bolt\Forms\BootstrapForm;

use PhpStrike\app\components\CrumbsComponent;

?>

<?php $this->start('header') ?>
<!-- Include SummerNote Editor stylesheet -->
<link href="<?= asset("packages/summernote/summernote-lite.min.css") ?>" rel="stylesheet">
<?php $this->end() ?>


<?php $this->start('content') ?>
<?= renderComponent(CrumbsComponent::class, ['name' => 'Edit Ticket']); ?>
<section class="section">

    <div class="row g-5 mb-3">
        <div class="col-md-5 col-lg-4 order-md-last">
            <img src="<?= get_image("", "default"); ?>" alt="" class="mx-auto d-block preview" style="height:250px;width:300px;object-fit:cover;border-radius: 10px;cursor: pointer;">
        </div>
        <div class="col-md-7 col-lg-8">
            <form action="" method="post">
                <div class="row g-3">

                    <?= BootstrapForm::selectField("Event", "event_id", $ticket["event_id"] ?? '', $eventOpts, ['class' => 'form-control'], ['class' => 'col-sm-12 mb-3'], $errors) ?>

                    <?= BootstrapForm::inputField("Type (e.g: VIP, Regular)", "type", old_value("type", $ticket["type"] ?? ''), ['class' => 'form-control'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::textareaField("Details", "details", old_value("details", $ticket["details"] ?? ''), ['class' => 'form-control summernote'], ['class' => 'col-sm-12'], $errors) ?>

                    <?= BootstrapForm::inputField("Price", "price", old_value("price", $ticket["price"] ?? ''), ['class' => 'form-control', 'type' => 'amount'], ['class' => 'col-sm-7'], $errors) ?>

                    <?= BootstrapForm::inputField("Quantity", "quantity", old_value("quantity", $ticket["quantity"] ?? ''), ['class' => 'form-control', 'type' => 'number'], ['class' => 'col-sm-5'], $errors) ?>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-warning btn-lg" type="submit">Update</button>
            </form>
        </div>
    </div>

</section>
<?php $this->end() ?>

<?php $this->start("script") ?>
<script src="<?= asset("packages/summernote/summernote-lite.min.js") ?>"></script>
<script>
    $('.summernote').summernote({
        placeholder: 'Ticket Details',
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
