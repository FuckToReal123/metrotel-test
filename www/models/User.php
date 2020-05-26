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

    /** @var boolean Авторизован? */
    private $isUthorized = false;
    /** @var string md5 хэш пароля  */
    private $pwdHash;

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
                    'max' => 15
                ]
            ],
            [
                'attributes' => ['name'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => false
                ]
            ],
            [
                'attributes' => ['password', 'passwordRepeat'],
                'validator' => 'password',
                'params' => [
                    'allowEmpty' => false,
                    'min' => 6,
                    'max' => 12
                ]
            ],
            [
                'attributes' => ['isUthorized'],
                'validator' => 'bool',
                'params' => [
                    'allowEmpty' => false,
                ]
            ],
            [
                'attributes' => ['captcha'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => false,
                    'max' => 4
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
        if ($this->password !== $this->passwordRepeat) {
            return false;
        }

        return parent::validate();
    }

    /**
     * Проверяет есть ли пользователь с заданными логином и паролем.
     * Если такой есть, устанавливаем флаг авторизации.
     */
    public function login()
    {
        $findUser = $this->find([
            'login' => $this->login,
            'pwd_hash' => $this->convertPassword()
        ])->one();

        if ($findUser) {
            $this->isUthorized = true;
        }
    }

    /**
     * Регистрация нового пользователя
     */
    public function register()
    {

    }

    /**
     * Конфертим пароль, чтобы не хранить его в открытом виде
     */
    private function convertPassword()
    {
        $this->pwdHash = md5($this->password);
    }
}
