<?php


namespace core\validators;


class PasswordValidator extends StringValidator
{
    public function validate()
    {
        return (preg_match('~[0-9]+~', $this->value) === 1 && parent::validate());
    }
}
