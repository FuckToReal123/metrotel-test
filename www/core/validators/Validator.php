<?php


namespace core\validators;


use core\base\BaseObject;

/**
 * Class Validator
 */
class Validator extends BaseObject
{
    public static function createValidator($name, $params = [])
    {
        $validatorClass = ucfirst($name) . 'Validator';

        return new $validatorClass($params);
    }
}
