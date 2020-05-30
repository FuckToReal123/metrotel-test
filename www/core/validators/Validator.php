<?php


namespace core\validators;


/**
 * Class Validator
 */
abstract class Validator
{
    /** @var bool Может ли быть пустым? */
    public $allowEmpty = true;
    /** @var mixed Валидируемое значение */
    public $value;
    /** @var string Сообщение об ошибке, если есть */
    public $message;

    /**
     * Validator constructor.
     *
     * @param mixed $value
     * @param array $params
     */
    public function __construct($value, $params = [])
    {
        $this->value = $value;
        $this->load($params);
    }

    /**
     * Создание валидатора
     *
     * @param string $class
     * @param mixed $value
     * @param array $params
     * @return Validator
     */
    public static function createValidator($class, $value, $params = [])
    {
        return new $class($value, $params);
    }

    /**
     * Валидация
     *
     * @return bool
     */
    abstract public function validate();

    /**
     * Заполняет поля класса
     *
     * @param array $values
     */
    public function load($values)
    {
        if (is_array($values)) {
            foreach ($values as $attribute => $value) {
                if (property_exists($this, $attribute) && $attribute !== 'value') {
                    $this->$attribute = $value;
                }
            }
        }
    }
}
