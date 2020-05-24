<?php


namespace controllers;


use core\base\BaseController;

class ErrorController extends BaseController
{
    public function action404()
    {
        $this->render('404', ['message' => 'asd']);
    }
}
