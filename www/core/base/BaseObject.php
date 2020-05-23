<?php


namespace core\base;


class BaseObject
{
    /**
     * Получение существующего поля класса
     *
     * @param $name
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \Exception('Получение не существующего пля: ' . get_class($this) . '::' . $name);
    }

    /**
     * Установка существующего поля класса
     *
     * @param $name
     * @param $value
     *
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }

        throw new \Exception('Установка не существующего поля: ' . get_class($this) . '::' . $name);
    }

    /**
     * Проверяет задано ли поле
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        return false;
    }

    /**
     * Устанавливает поле в null
     *
     * @param $name
     *
     * @throws \Exception
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new \Exception('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }
}
