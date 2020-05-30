<?php
/**
 * @var $model \models\Contact
 */
?>

<form action="<?= $model->id ? "/contact/update/{$model->id}" : "/contact/create/" ?>" class="login-form">
    <div class="form-group">
        <label for="name"><?= $model->getLabel('name') ?></label>
        <input
            name="name"
            type="text"
            id="name"
            class="form-control"
            required
            value="<?= $model->name ?>"
            placeholder="<?= $model->getLabel('name') ?>"
        >
    </div>
    <div class="form-group">
        <label for="last_name"><?= $model->getLabel('last_name') ?></label>
        <input
            name="last_name"
            type="text"
            id="last_name"
            class="form-control"
            value="<?= $model->last_name ?>"
            placeholder="<?= $model->getLabel('last_name') ?>"
        >
    </div>
    <div class="form-group">
        <label for="phone"><?= $model->getLabel('phone') ?></label>
        <input
            name="phone"
            type="tel"
            id="phone"
            class="form-control"
            placeholder="<?= $model->getLabel('phone') ?>"
        >
    </div>
    <div class="form-group">
        <label for="email"><?= $model->getLabel('email') ?></label>
        <input
                name="email"
                type="email"
                id="email"
                class="form-control"
                placeholder="<?= $model->getLabel('email') ?>"
        >
    </div>
    <div class="form-group">
        <label for="photo"><?= $model->getLabel('photo') ?></label>
        <input
                name="photo"
                type="file"
                id="photo"
                class="form-control"
                placeholder="<?= $model->getLabel('photo') ?>"
        >
    </div>
    <br>
    <button class="btn btn-lg btn-primary" type="submit">Сохранить</button>
</form>
