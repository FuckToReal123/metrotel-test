<?php
use \core\application\components\Session;
?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">
    <script src="/assets/js/jquery-3.5.1.min.js" type="text/javascript"></script>
    <script src="/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/js/main.js" type="text/javascript"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Телефонная книга</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login/">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/register/">Регистрация</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="wrap text-center">
        <main role="main" class="container">
            <?php if ($flash = Session::get('flash')): ?>
                <div class="text-left alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
                <?php Session::delete('flash'); ?>
            <?php endif; ?>

