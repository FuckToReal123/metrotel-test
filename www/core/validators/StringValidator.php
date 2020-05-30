<?php


namespace core\validators;


class StringValidator extends Validator
{
    /** @var int Максимальная длина строки */
    public $max;
    /** @var int Минимальная длина строки */
    public $min = 0;
    /** @var bool Только латинские буквы? */
    public $latinOnly = false;

    public function validate()
    {
        $result = true;

        if ($this->allowEmpty && !$this->value) {
            return true;
        }

        $length = strlen($this->value);

        if (!$this->allowEmpty && $length < $this->min) {
            $result = false;
        }

        if ($length > $this->max) {
            $result = false;
        }

        if ($this->latinOnly) {
            $result = (preg_match('/^[a-zA-Z0-9\s]/', $this->value) === 1);
        }

        return $result;
    }
}
