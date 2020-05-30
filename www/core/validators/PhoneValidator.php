<?php


namespace core\validators;


class PhoneValidator extends Validator
{
    /** @var int Максимальная длина телефона */
    public $max;
    /** @var int Минимальная длина телефона */
    public $min;
    /** @var string Регулярка для проверки телефонных номеров */
    private $regex = '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/';

    public function validate()
    {
        $result = true;

        if ($this->allowEmpty && !$this->value) {
            return true;
        }

        $length = strlen($this->value);

        if ($length > $this->max || $length < $this->min) {
            $result = false;
        }

        if (preg_match($this->regex, $this->value) !== 1) {
            $result = false;
        }

        return $result;
    }
}
