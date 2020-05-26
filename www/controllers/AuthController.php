<?php


namespace controllers;


use core\base\BaseController;

class AuthController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index', ['a' => 'fuck']);
    }
}
