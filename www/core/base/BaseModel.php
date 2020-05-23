<?php


namespace core\base;

use core\validators\Validator;

/**
 * Class BaseModel
 */
abstract class BaseModel extends BaseObject
{
    /** @var array[] Массив ошибок */
    private $errors = [];

    /** @var array Массив валидаторов */
    private $validators;

    /**
     * Валидация полей
     *
     * @return bool
     */
    public function validate()
    {
        $validationRules = $this->getRules();



        return !$this->hasErrors();
    }

    /**
     * Есть ли ошибки?
     *
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors);
    }

    /**
     * Получить ошибку для атрибута
     *
     * @param $attribute
     * @return string
     */
    public function getError($attribute)
    {
        return isset($this->errors[$attribute]) ? implode('. ', $this->errors[$attribute]) : null;
    }

    /**
     * Получить все ошибки
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Добавить ошибку для поля
     *
     * @param $attribute
     * @param $message
     */
    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * Получение правил валидации
     *
     * @return array
     */
    protected abstract function getRules();

    /**
     *
     */
    private function createValidators()
    {
        foreach ($this->getRules() as $validatorName => $params) {
            $this->validators = Validator::createValidator($validatorName, $params);
        }
    }
}
