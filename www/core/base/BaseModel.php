<?php


namespace core\base;

use core\base\BaseObject;
use \ReflectionClass;

class BaseModel extends BaseObject
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
     * Получение названий полей
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
     * Получение массива полей
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
     * Заполнение полей класса
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
