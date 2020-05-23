<?php

try {
    ini_set('display_errors', getenv('IS_DEV'));

    require_once __DIR__ . 'config/consts.php';
    require_once __DIR__ . 'core/Autoloader.php';

    Autoloader::register();

    core\application\Application::getInstance()->start();
} catch (Exception $e) {

}


