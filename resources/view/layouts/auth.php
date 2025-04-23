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
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Standard favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_image("", "default") ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= get_image("", "default") ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= get_image("", "default") ?>">

    <!-- Apple Touch Icon (For iOS) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= get_image("", "default") ?>">

    <link rel="stylesheet" href="<?= asset("dist/css/all.min.css") ?>">

    <link href="<?= asset("dist/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?= asset("packages/toastr/toastr.min.css") ?>">

    <title>Evently | <?= $this->getTitle() ?></title>
    <?php $this->content('header') ?>
    <style>
        :root {
            --white: #FFFFFF;
            --light-green: #77AD78;
            --dark-blue: #175676;
            --medium-blue: #4BA3C3;
            --light-blue: #CCE6F4;
        }

        body {
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .split-container {
            min-height: 100vh;
        }

        .brand-side {
            background-color: var(--dark-blue);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
        }

        .login-side {
            background-color: var(--white);
            padding: 2rem;
            display: flex;
            align-items: center;
        }

        .logo {
            color: var(--white);
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            width: 100%;
        }

        .logo a{
            text-decoration: none;
            color: var(--white);
        }

        .tagline {
            color: var(--light-blue);
            font-size: 1.2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .brand-image {
            max-width: 80%;
            display: block;
            margin: 2rem auto 0;
        }

        .login-form-container,
        .form-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            padding: 1rem;
        }

        .login-title,
        .form-title {
            color: var(--dark-blue);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--medium-blue);
            border-color: var(--medium-blue);
            padding: 10px 20px;
            font-weight: 600;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
            border-color: var(--dark-blue);
        }

        .form-control:focus {
            border-color: var(--medium-blue);
            box-shadow: 0 0 0 0.25rem rgba(75, 163, 195, 0.25);
        }

        .forgot-password {
            color: var(--medium-blue);
            text-decoration: none;
        }

        .forgot-password:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        .sign-up-text {
            color: var(--dark-blue);
        }

        .sign-up-link {
            color: var(--light-green);
            text-decoration: none;
            font-weight: 600;
        }

        .sign-up-link:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
        }

        /* Mobile styles */
        @media (max-width: 767px) {
            .brand-side {
                min-height: 200px;
                padding: 1.5rem;
            }

            .logo {
                font-size: 2.5rem;
            }

            .tagline {
                font-size: 1rem;
            }

            .login-form-container,
            .form-container {
                padding: 0;
            }

            .login-title,
            .form-title {
                font-size: 1.5rem;
            }

            .brand-image {
                max-width: 70%;
            }

            .mobile-brand {
                text-align: center;
                margin-bottom: 2rem;
            }
        }
    </style>
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
            unset($_SESSION['__bv_flash_toastr']); // Remove the toastr from the session
            ?>
            toastr.<?= $toastr['type'] ?>("<?= $toastr['message'] ?>");
        <?php endif; ?>
    </script>
    <?php $this->content('script') ?>
</body>

</html>