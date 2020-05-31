<?php


namespace controllers;


use core\application\components\ServerUtils;
use core\application\components\Session;
use core\application\components\User;
use core\base\BaseController;
use models\Contact;

/**
 * Class ContactController
 */
class ContactController extends BaseController
{
    /**
     * Список контактов
     *
     * @param null $sort
     */
    public function actionIndex($sort = null)
    {
        $model = new Contact();

        $this->render('index', ['model' => $model]);
    }

    /**
     * Изменение контакта
     *
     * @param $id
     */
    public function actionUpdate($id)
    {
        $model = Contact::model()->find(['id' => $id])->one();

        if (ServerUtils::isPost()) {
            $model->load($_POST);

            $allowedFileTypes = ['image/jpeg', 'image/png'];

            if (!in_array($_FILES['photo']['type'], $allowedFileTypes)) {
                $model->addError('photo', 'Неверный тип файла');
                return $this->render('create', ['model' => $model, 'action' => "/contact/update/{$id}"]);
            }

            $fileName = md5(uniqid(basename($_FILES['photo']['name'])));
            $fileNameArr = explode('.', $_FILES['photo']['name']);
            $ext = end($fileNameArr);
            $newFileName = PHOTOS_DIR . $fileName . '.' . $ext;

            move_uploaded_file($_FILES['photo']['tmp_name'], $newFileName);

            $model->photo = $newFileName;

            if ($model->update()) {
                Session::setFlash('success', 'Контакт успешно обновлён.');
                BaseController::redirect('/contact/index/');
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

        $this->render('create', ['model' => $model, 'action' => "/contact/update/{$id}"]);
    }

    /**
     * Создание контакта
     */
    public function actionCreate()
    {
        $model = new Contact();

        if (ServerUtils::isPost()) {
            $model->load($_POST);

            $allowedFileTypes = ['image/jpeg', 'image/png'];

            if (!in_array($_FILES['photo']['type'], $allowedFileTypes)) {
                $model->addError('photo', 'Неверный тип файла');
                return $this->render('create', ['model' => $model, 'action' => '/contact/create/']);
            }

            $fileName = md5(uniqid(basename($_FILES['photo']['name'])));
            $fileNameArr = explode('.', $_FILES['photo']['name']);
            $ext = end($fileNameArr);
            $newFileName = PHOTOS_DIR . $fileName . '.' . $ext;

            move_uploaded_file($_FILES['photo']['tmp_name'], $newFileName);

            $model->photo = $newFileName;

            if ($model->save()) {
                Session::setFlash('success', 'Контакт добавлен.');
                BaseController::redirect('/contact/index/');
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

        $this->render('create', ['model' => $model, 'action' => '/contact/create/']);
    }

    /**
     * Удаление контакта
     *
     * @param $id
     */
    public function actionDelete($id)
    {
        $result = 'error';

        if (ServerUtils::isPost() && Contact::model()->deleteByPk($id)) {
            $result = 'success';
        }

        echo $result;
    }

    /**
     * Получение сортированного тела табллицы
     *
     * @param $order
     * @param $sortBy
     * @return void
     */
    public function actionGetSortedTbody($order, $sortBy)
    {
        if (!ServerUtils::isAjax()) {
            $errorController = new ErrorController();

            return $errorController->action404();
        }

        $userId = User::getCurUser()->id;
        $contacts = Contact::model()->find("user_id = {$userId} ORDER BY {$order} {$sortBy}")->all();

        $this->renderPartial('_sortedTbody', ['contacts' => $contacts]);
    }
}
