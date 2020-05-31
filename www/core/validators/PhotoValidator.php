<?php


namespace core\validators;


class PhotoValidator extends Validator
{
    /** @var int Максимальная длина строки */
    public $max;
    /** @var array */
    public $extensions;

    public function validate()
    {
        $result = true;

        if ($this->allowEmpty && !$this->value) {
            return true;
        }

        $length = strlen($this->value);

        if ($length > $this->max) {
            $result = false;
        } else {
            $extRegexPart = implode('|', $this->extensions);
            $result = (preg_match("/^[a-zA-Z0-9\/]+\.({$extRegexPart})/", $this->value) === 1);
        }

        return $result;
    }
}
