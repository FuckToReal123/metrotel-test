<?php
/**
 * @var $this \controllers\ContactController
 * @var $model \models\Contact
 * @var $action string
 */
?>

<h1>Изменение контакта</h1>

<?php $this->renderPartial('_form', ['model' => $model, 'action' => $action]); ?>
