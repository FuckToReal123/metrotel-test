<?php


namespace core\base;


use ReflectionClass;

class BaseObject
{
    /**
     * BaseModel constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            $this->load($config);
        }

        $this->init();
    }

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
            throw new \Exception('Снятие свойства только для чтения: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Получает названя полей
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function attributes()
    {
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }
        return $names;
    }

    /**
     * Получает массив полей
     *
     * @param null $names
     * @param array $except
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getAttributes($names = null, $except = [])
    {
        $values = [];

        if ($names === null) {
            $names = $this->attributes();
        }

        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }

        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    /**
     * Заполняет поля класса
     *
     * @param array $values
     */
    public function load($values)
    {
        if (is_array($values)) {
            foreach ($values as $attribute => $value) {
                if (property_exists($this, $attribute)) {
                    $this->$attribute = $value;
                }
            }
        }
    }

    /**
     * Метод избавляющий от необходимости переопределять конструктор
     */
    public function init()
    {
    }
}
