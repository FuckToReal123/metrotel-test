<?php


namespace core\application\components;


final class Server
{
    /**
     * @return string
     */
    public static function getUri()
    {
        if (!empty($uri) && $uri != '/') {
            return strtok(trim($_SERVER['REQUEST_URI'], '/'), '?');
        }

        return '/';
    }
}
