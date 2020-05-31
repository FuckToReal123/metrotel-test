<?php
/**
 * @var $model models\Contact
 */

use \core\application\components\Session;
use \core\application\components\User;
?>

<h1>Список контактов</h1>

<table class="table table-bordered">
    <thead class="thead-dark">
    <tr>
        <th scope="col"></th>
        <th scope="col">
            <?= $model->getLabel('photo') ?>
        </th>
        <th scope="col">
            <a href="#" class="js-sort-list" data-sort="name">
                <?= $model->getLabel('name') ?>
                <span class="triangle"></span>
            </a>
        </th>
        <th scope="col">
            <a href="#" class="js-sort-list" data-sort="last_name">
                <?= $model->getLabel('last_name') ?>
                <span class="triangle"></span>
            </a>
        </th>
        <th scope="col">
            <a href="#" class="js-sort-list" data-sort="phone">
                <?= $model->getLabel('phone') ?>
                <span class="triangle"></span>
            </a>
        </th>
        <th scope="col">
            <a href="#" class="js-sort-list" data-sort="email">
                <?= $model->getLabel('email') ?>
                <span class="triangle"></span>
            </a>
        </th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($model->find(['user_id' => User::getCurUser()->id])->all() as $contact): ?>
            <tr>
                <td>
                    <a href="/contact/update/<?= $contact->id ?>">Изменить</a>
                    <a class="js-delete-contact" href="#" data-id="<?= $contact->id ?>">Удалить</a>
                </td>
                <td>
                    <img
                            src="/<?= str_replace(ROOT_DIR, '', $contact->photo) ?>"
                            alt="<?= $model->getLabel('photo') ?>"
                            class="contact-photo"
                    >
                </td>
                <td>
                    <?= $contact->name ?>
                </td>
                <td>
                    <?= $contact->last_name ?>
                </td>
                <td>
                    <?= $contact->phone ?>
                </td>
                <td>
                    <?= $contact->email ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
