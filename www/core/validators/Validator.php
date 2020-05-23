<?php


namespace core\validators;


use core\base\BaseObject;

/**
 * Class Validator
 */
abstract class Validator extends BaseObject
{
    /** @var bool Может ли быть пустым? */
    public $allowEmpty = true;
    /** @var mixed Валидируемое значение */
    public $value;
    /** @var string Сообщение об ошибке, если есть */
    protected $message;

    /**
     * Создание валидатора
     *
     * @param $name
     * @param array $params
     * @return mixed
     */
    public static function createValidator($name, $value, $params = [])
    {
        $validatorClass = ucfirst($name) . 'Validator';

        return new $validatorClass($value, $params);
    }

    /**
     * Валидация
     *
     * @return bool
     */
    abstract public function validate();

    public function getMessage()
    {
        return $this->message;
    }
}
