<?php


namespace controllers;


use core\application\Application;
use core\application\components\Captcha;
use core\application\components\ServerUtils;
use core\application\components\Session;
use core\base\BaseController;
use models\User;

/**
 * Class AuthController
 */
class AuthController extends BaseController
{
    /** @inheritDoc */
    public $layoutName = 'auth';

    /**
     * Регистрация пользователя
     */
    public function actionRegister()
    {
        $model = new User();

        if (ServerUtils::isPost()) {
            $model->load($_POST);

            if (!$model->checkExists()) {
                if ($model->save()) {
                    Session::setFlash('success', 'Вы успешно зарегистрировались.');
                    $this->redirect('/auth/login/');
                } else {
                    $errorsText = "<ul>";

                    foreach ($model->getErrors() as $attribute => $errors) {
                        $errorText = implode(', ', $errors);
                        $errorsText .= "<li>{$model->getLabel($attribute)}: {$errorText}</li>";
                    }

                    $errorsText .= "</ul>";

                    Session::setFlash('danger', $errorsText);
                }
            }
        }

        return $this->render('register', ['model' => $model]);
    }

    /**
     * Авторизация пользователя
     */
    public function actionLogin()
    {
        $model = new User();

        if (ServerUtils::isPost()) {
            $model->load($_POST);

            if ($model->validate() && $model->checkExists()) {
                $newmodel = $model->find([
                    'login' => $model->login,
                    'pwd_hash' => $model->pwd_hash
                ])->one();

                $model->load(array_filter($newmodel->getAttributes()));
                \core\application\components\User::setCurUser($model);
                Session::setFlash('success', 'Добро пожаловать ' . $model->login . '!');
                $this->redirect('/contact/index/');
            } else {
                $errorsText = "<ul>";

                foreach ($model->getErrors() as $attribute => $errors) {
                    $errorText = implode(', ', $errors);
                    $errorsText .= "<li>{$model->getLabel($attribute)}: {$errorText}</li>";
                }

                $errorsText .= "</ul>";

                Session::setFlash('danger', $errorsText);
            }
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Выход
     */
    public function actionLogout()
    {
        Session::delete('user');
        self::redirect('/auth/login/');
    }

    /**
     * Получение капчи
     */
    public function actionCaptcha()
    {
        $captchaCode = Captcha::generateCode();

        Session::set('captchaCode', $captchaCode);

        $image = Captcha::createImage($captchaCode);

        header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-type: image/gif");

        imagegif($image);
        imagedestroy($image);
    }
}
