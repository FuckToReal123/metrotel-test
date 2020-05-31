<?php


namespace core\application\components;

use controllers\ErrorController;
use core\application\Application;
use core\base\BaseController;

/**
 * Class Router
 */
final class Router
{
    /** @var array Маршруты */
    private static $routes = [];

    /**
     * Запуск обработки маршрутов
     */
    public static function run()
    {
        $uri = ServerUtils::getUri();

        if (User::isGuest()
            && strpos($uri, 'auth') === false
        ) {
            BaseController::redirect('/auth/login');
        }

        if (!array_key_exists($uri, self::$routes)) {
            $uriParts = explode('/', $uri);
            $controllerName = ucfirst(array_shift($uriParts)) . 'Controller';
            $actionName = 'action' . ucfirst(array_shift($uriParts));

            if(self::callAction($controllerName, $actionName, $uriParts)) {
                self::addRoute($uri, $controllerName, $actionName, $uriParts);
            }
        }
    }

    /**
     * Вызывает экшен
     *
     * @param $controllerName
     * @param $actionName
     * @param array $params
     * @return bool
     */
    private static function callAction($controllerName, $actionName, $params = []) {
        $controllerClass = 'controllers\\' . $controllerName;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $actionName)) {
                return call_user_func_array([$controller, $actionName], $params);
            }
        }

        return self::httpNotFound();
    }

    /**
     * Добавляет маршрут
     *
     * @param $uri
     * @param $controllerName
     * @param $actionName
     * @param array $params
     */
    private static function addRoute($uri ,$controllerName, $actionName, $params = [])
    {
        self::$routes[$uri] = [
            'controller' => $controllerName,
            'action' => $actionName,
            'params' => $params
        ];
    }

    /**
     * Отпарвляет на 404
     *
     * @return bool
     */
    private static function httpNotFound()
    {
        $controller = new ErrorController();
        $controller->action404();
        return false;
    }
}
