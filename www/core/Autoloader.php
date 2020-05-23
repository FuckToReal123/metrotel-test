<?php

/**
 * Class Autoloader
 */
class Autoloader
{
    /**
     * Регистрирует автолоадер
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'load']);
    }

    /**
     * Подгружает файл класса
     * @param string $className Неймспес и имя класса
     */
    private static function load($className)
    {
        $filePath = ROOT_DIR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
}
