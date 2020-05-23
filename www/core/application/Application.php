<?php


namespace core\application;

use core\application\components\DbConnection;
use core\application\components\Router;
use core\application\components\Session;
use models\User;

/**
 * Class Application
 */
class Application
{
    /** @var Session */
    public $session;
    /** @var User */
    public $user;
    /** @var DbConnection */
    public $db;


    /** @var Application */
    private static $instance;
    /** @var Router */
    private $router;

    /**
     * Получение экземпляра приложения
     *
     * @return Application|static
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Запуск приложения
     */
    public function start()
    {
        $this->session->start();
        $this->router->run();
        $this->db->getInstance();
    }

    /**
     * Удобный вид дампа
     *
     * @param $data
     * @param bool $stop
     */
    public static function dump($data, $stop = false)
    {
        echo '<pre>';
        var_export($data);
        echo '</pre>';

        if ($stop) {
            die;
        }
    }

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $this->session = new Session();
        $this->router = new Router();
        $this->db = DbConnection::getInstance();
    }

    private function __clone() {}
    private function __wakeup() {}
}
