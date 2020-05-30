<?php


namespace core\validators;


use core\application\components\Session;

class CaptchaValidator extends StringValidator
{
    public function validate()
    {
        return ($this->value === Session::get('captchaCode') && parent::validate());
    }
}
