<?php


namespace controllers;


use core\base\BaseController;

/**
 * Class ContactController
 */
class ContactController extends BaseController
{
    /**
     * Список контактов
     */
    public function actionIdex()
    {
        $model = new Contact();


    }

    /**
     * Просмотр контакта
     *
     * @param $id
     */
    public function actionView($id)
    {

    }

    /**
     * Изменение контакта
     *
     * @param $id
     */
    public function actionUpdate($id)
    {

    }

    /**
     * Создание контакта
     *
     * @param $id
     */
    public function actionCreate($id)
    {

    }

    /**
     * Удаление контакта
     *
     * @param $id
     */
    public function actionDelete($id)
    {

    }
}
