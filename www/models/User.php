<?php


namespace models;


use core\base\BaseDbObjet;

class User extends BaseDbObjet
{
    /** @var int ID пользователя */
    public $id;
    /** @var string Логин */
    public $login;
    /** @var string Имя пользователя */
    public $name;
    /** @var string Пароль */
    public $password;
    /** @var string Повтор пароля */
    public $passwordRepeat;
    /** @var string Капча */
    public $captcha;
    /** @var string Хэш пароля */
    public $pwd_hash;

    /** @inheritDoc */
    public function primaryKey()
    {
        return 'id';
    }

    /** @inheritDoc */
    public function tableName()
    {
        return 'users';
    }

    /** @inheritDoc */
    public function getRules()
    {
        return [
            [
                'attributes' => ['login'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => false,
                    'min' => 3,
                    'max' => 15,
                    'latinOnly' => true,
                    'message' => 'Логин может содержать только латинские буквы и цифры. Длина от 3 до 15 символов.'
                ]
            ],
            [
                'attributes' => ['name'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => false,
                    'max' => 100,
                ]
            ],
            [
                'attributes' => ['password', 'passwordRepeat'],
                'validator' => 'password',
                'params' => [
                    'allowEmpty' => false,
                    'min' => 6,
                    'max' => 12,
                    'latinOnly' => true,
                    'message' => 'Пароли не совпадают.
                                  Либо не удовлетвояют условиям. 
                                  Пароль должен содержать от 6 до 12 символов латинского алфавита и цифр.'
                ]
            ],
            [
                'attributes' => ['captcha'],
                'validator' => 'captcha',
                'params' => [
                    'allowEmpty' => false,
                    'max' => 4,
                    'latinOnly' => true,
                    'message' => 'Неверный проверочный код.'
                ]
            ],
        ];
    }

    /** @inheritDoc */
    public function labels()
    {
        return [
            'name' => 'ФИО',
            'login' => 'Логин',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повторите пароль',
            'captcha' => 'Проверочный код'
        ];
    }

    /** @inheritDoc */
    public function validate()
    {
        $this->pwd_hash = $this->convertPassword();

        if ($this->password !== $this->passwordRepeat) {
            $this->addError('password', 'Пароли не совпадают.');
            $this->addError('passwordRepeat', 'Пароли не совпадают.');
            return false;
        }

        return parent::validate();
    }

    /**
     * Проверяет есть ли такой пользователь
     *
     * @return bool
     */
    public function checkExists()
    {
        return (bool)$this->find([
            'login' => $this->login,
        ])->one();
    }

    /**
     * Конфертим пароль, чтобы не хранить его в открытом виде
     */
    private function convertPassword()
    {
        return md5($this->password);
    }
}
