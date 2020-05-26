<?php


namespace core\application\components;

use PDO;

class DbConnection
{
    /** @var PDO  */
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
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Возвращает название базы
     *
     * @return string
     */
    public static function getDbName()
    {
        return getenv('MYSQL_DATABASE');
    }

    /**
     * DbConnection constructor.
     */
    private function __construct () {
        $config = require_once ROOT_DIR . 'config/db.php';

        $this->instance = new PDO(
            $config['dsn'],
            $config['username'],
            $config['passwd'],
            $config['options']
        );

    }

    private function __clone () {}
    private function __wakeup () {}
}
