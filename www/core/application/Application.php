<?php


namespace core\application;


use core\application\components\DbConnect;
use core\application\components\Router;
use core\application\components\Session;
use models\User;

class Application
{
    /** @var Session */
    public $session;
    /** @var User */
    public $user;
    /** @var DbConnect */
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
            self::$instance = new static();
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
        $this->db->connect();
    }

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $this->session = new Session();
        $this->router = new Router();
        $this->db = new DbConnect();
    }

    protected function __clone() {}

    protected function __wakeup() {}
}
