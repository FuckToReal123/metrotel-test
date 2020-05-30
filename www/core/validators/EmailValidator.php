<?php


namespace core\validators;

/**
 * Class EmailValidator
 */
class EmailValidator extends Validator
{
    /** @var int Максимальная длина email */
    public $max;
    /** @var string Регулярка для прокрки корректности */
    private $regex = '/.+@.+\..+/i';

    public function validate()
    {
        $result = true;

        if ($this->allowEmpty && !$this->value) {
            return true;
        }

        if (strlen($this->value) > $this->max) {
            $result = false;
        }

        if (preg_match($this->regex, $this->value) !== 1) {
            $result = false;
        }

        return $result;
    }
}
