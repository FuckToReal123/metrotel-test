<?php

try {
    ini_set('display_errors', 'On');
    ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_startup_errors', 'Off');
    ini_set('display_startup_errors', 'Off');

    require __DIR__ . '/config/consts.php';
    require __DIR__ . '/core/Autoloader.php';

    Autoloader::register();

    core\application\Application::getInstance()->start();
} catch (Exception $e) {
    echo '<pre>';
    var_export($e->getMessage());
    echo '</pre>';
}
