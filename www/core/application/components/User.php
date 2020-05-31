<?php


namespace core\application\components;

/**
 * Class User
 */
final class User
{
    /**
     * Пользователь авторизован?
     *
     * @return bool
     */
    public static function isGuest()
    {
        return empty(Session::get('user'));
    }

    /**
     * Устанавливает текущего юзера
     *
     * @param \models\User $user
     */
    public static function setCurUser($user)
    {
        Session::set('user', $user);
    }

    /**
     * Возвращает модель текущего юзера
     *
     * @return mixed|null
     */
    public static function getCurUser()
    {
        return Session::get('user');
    }
}
