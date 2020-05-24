<?php

try {
    ini_set('display_errors', DEV_MODE);

    require __DIR__ . '/config/consts.php';
    require __DIR__ . '/core/Autoloader.php';

    Autoloader::register();

    core\application\Application::getInstance()->start();
} catch (Exception $e) {
    echo '<pre>';
    var_export($e->getMessage());
    echo '</pre>';
}


