<?php


namespace models;


use core\application\components\ServerUtils;
use core\base\BaseDbObjet;

class Contact extends BaseDbObjet
{
    /** @var int ID контакта */
    public $id;
    /** @var int ID пользователя */
    public $user_id;
    /** @var string Номер телефона */
    public $phone;
    /** @var string e-mail */
    public $email;
    /** @var string Файл фото */
    public $photo;
    /** @var string Имя */
    public $name;
    /** @var string Фамилия */
    public $last_name;

    /** @inheritDoc */
    public function primaryKey()
    {
        return 'id';
    }

    /** @inheritDoc */
    public function tableName()
    {
        return 'contact';
    }

    /** @inheritDoc */
    public function getRules()
    {
        return [
            [
                'attributes' => ['phone'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => true,
                    'min' => 7,
                    'max' => 16,
                    'message' => 'Неверный формат телефонного номера.'
                ]
            ],
            [
                'attributes' => ['email'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => true,
                    'max' => 100,
                    'latinOnly' => true,
                    'message' => 'Неверный формат электронной почты.'
                ]
            ],
            [
                'attributes' => ['photo'],
                'validator' => 'photo',
                'params' => [
                    'allowEmpty' => true,
                    'max' => 400,
                    'extensions' => ['jpeg', 'jpg', 'png'],
                    'message' => 'Не удалось загрузить файл.'
                ]
            ],
            [
                'attributes' => ['name'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => false,
                    'max' => 100,
                    'latinOnly' => false,
                    'message' => 'Неверный формат.'
                ]
            ],
            [
                'attributes' => ['last_name'],
                'validator' => 'string',
                'params' => [
                    'allowEmpty' => true,
                    'max' => 100,
                    'latinOnly' => false,
                    'message' => 'Неверный формат.'
                ]
            ],
        ];
    }

    /** @inheritDoc */
    public function labels()
    {
        return [
            'phone' => 'Номер телефона',
            'email' => 'Электронная почта',
            'photo' => 'Фото',
            'name' => 'Имя',
            'last_name' => 'Фамилия',
        ];
    }

    /** @inheritDoc */
    public function init()
    {
        $this->user_id = \core\application\components\User::getCurUser()->id;

        parent::init();
    }
}
