<?php


namespace core\base;


use core\validators\Validator;

/**
 * Class BaseModel
 */
class BaseModel extends BaseObject
{
    /** @var array[] Массив ошибок */
    private $errors = [];
    /** @var array Массив валидаторов */
    private $validators;

    /**
     * Получение лейбля для поля
     *
     * @param string $key
     * @return mixed
     */
    public function getLabel($key)
    {
        $labels = $this->labels();

        if (isset($labels[$key])) {
            return $labels[$key];
        }

        return $key;
    }

    /**
     * Валидация полей
     *
     * @return bool
     */
    public function validate()
    {
        $this->createValidators();

        foreach ($this->validators as $attribute => $validator) {
            if (!$validator->validate()) {
                $this->addError($attribute, $validator->message);
            }
        }

        return $this->hasErrors();
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
     * Создаёт валидаторы
     *
     * @throws \Exception
     */
    private function createValidators()
    {
        foreach ($this->getRules() as $rule) {
            $validatorClass = 'core\validators\\' . ucfirst($rule['validator']) . 'Validator';

            if (class_exists($validatorClass)) {
                foreach ($rule['attributes'] as $attribute) {
                    $this->validators[$attribute] = Validator::createValidator(
                        $validatorClass,
                        $this->$attribute,
                        $rule['params']
                    );
                }
            } else {
                throw new \Exception('Не существующий валидатор: ' . $rule['validator']);
            }
        }
    }
}
