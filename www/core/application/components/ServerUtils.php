<?php

namespace core\application\components;

/**
 * Class ServerUtils
 */
final class ServerUtils
{
    /** @var string Значение HTTP_X_REQUESTED_WITH при ajax-запросах */
    const AJAX_HTTP_X_REQUESTED_WITH = 'XMLHttpRequest';
    /** @var string Значение REQUEST_METHOD при post-запросах */
    const POST_REQUEST_METHOD = 'POST';

    /**
     * Получает запрошенный адрес
     *
     * @return string
     */
    public static function getUri()
    {
        if (!empty($uri) && $uri != '/') {
            return strtok(trim($_SERVER['REQUEST_URI'], '/'), '?');
        }

        return '/';
    }

    /**
     * Проверяет был ли это ajax-запрос
     *
     * @return bool
     */
    public static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return $_SERVER['HTTP_X_REQUESTED_WITH'] === self::AJAX_HTTP_X_REQUESTED_WITH;
        }

        return false;
    }

    /**
     * Проверяет был ли это post-запрос
     *
     * @return bool
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === self::POST_REQUEST_METHOD;
    }
}
