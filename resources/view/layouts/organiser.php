<?php

use celionatti\Bolt\Helpers\FlashMessages\BootstrapFlashMessage;

?>

<!DOCTYPE html>
<html lang="en_us">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Standard favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_image("", "default") ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= get_image("", "default") ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= get_image("", "default") ?>">

    <!-- Apple Touch Icon (For iOS) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= get_image("", "default") ?>">

    <link rel="stylesheet" href="<?= asset("dist/css/all.min.css") ?>">

    <link rel="stylesheet" href="<?= asset("dist/bootstrap/css/bootstrap.min.css") ?>">

    <link rel="stylesheet" href="<?= asset("dist/css/organiser.css") ?>">

    <link rel="stylesheet" href="<?= asset("packages/toastr/toastr.min.css") ?>">

    <title>Evently | <?= $this->getTitle() ?></title>
    <?php $this->content('header') ?>
</head>

<body>

    <?= BootstrapFlashMessage::alert(); ?>
    <!-- Your Content goes in here. -->
    <?php $this->content('content'); ?>

    <script src="<?= asset('dist/js/jquery-3.7.0.min.js'); ?>"></script>
    <script src="<?= asset('packages/toastr/toastr.min.js'); ?>"></script>
    <script src="<?= asset("dist/bootstrap/js/bootstrap.bundle.min.js") ?>"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        <?php if (isset($_SESSION['__bv_flash_toastr'])) : ?>
            <?php
            $toastr = $_SESSION['__bv_flash_toastr'];
            unset($_SESSION['__bv_flash_toastr']);
            ?>
            toastr.<?= $toastr['type'] ?>("<?= $toastr['message'] ?>");
        <?php endif; ?>
    </script>

    <?php $this->content('script') ?>
</body>

</html>