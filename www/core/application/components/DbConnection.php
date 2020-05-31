<?php


namespace core\application\components;

use PDO;

class DbConnection
{
    /** @var static  */
    private static $instance;
    /** @var array */
    private static $config;

    /**
     * Получает экземпляр класса
     *
     * @return DbConnection|PDO
     */
    public static function getInstance()
    {
        $config = require_once ROOT_DIR . 'config/db.php';

        if (!self::$instance) {
            self::$instance =  new PDO(
                $config['dsn'],
                $config['username'],
                $config['passwd'],
                $config['options']
            );
        }

        return self::$instance;
    }

    private function __clone () {}
    private function __wakeup () {}
}
