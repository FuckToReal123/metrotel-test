<?php
/**
 * @var $model \models\User
 */

use \core\application\components\Captcha;
?>

<form method="POST" action="/auth/login/" class="login-form">
    <div class="form-group">
        <label for="login"><?= $model->getLabel('login') ?><span class="required">*</span></label>
        <input
                name="login"
                type="text"
                id="login"
                class="form-control"
                required
                value="<?= $model->login ?>"
                placeholder="<?= $model->getLabel('login') ?>"
        >
    </div>
    <div class="form-group">
        <label for="password"><?= $model->getLabel('password') ?><span class="required">*</span></label>
        <input
                name="password"
                type="password"
                id="password"
                class="form-control"
                required
                value="<?= $model->password ?>"
                placeholder="<?= $model->getLabel('password') ?>"
        >
    </div>
    <div class="form-group">
        <label for="passwordRepeat"><?= $model->getLabel('passwordRepeat') ?><span class="required">*</span></label>
        <input
                name="passwordRepeat"
                type="password"
                id="passwordRepeat"
                class="form-control"
                required
                placeholder="<?= $model->getLabel('passwordRepeat') ?>"
        >
    </div>
    <br>
    <div class="form-group">
        <label for="captha">
            <?= $model->getLabel('captcha') ?><span class="required">*</span>
            <img
                    src="/auth/captcha/"
                    alt="captcha"
                    width="<?= Captcha::IMAGE_WIDTH ?>px"
                    height="<?= Captcha::IMAGE_HEIGHT ?>px"
            >
        </label>
        <input
                type="text"
                name="captcha"
                id="captcha"
                class="form-control"
                required
                placeholder="<?= $model->getLabel('captcha') ?>"
        >
    </div>
    <br>
    <button class="btn btn-lg btn-primary" type="submit">Вход</button>
</form>
