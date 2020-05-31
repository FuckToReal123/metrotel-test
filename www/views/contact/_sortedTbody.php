<?php
/**
 * @var $contacts \models\Contact[]
 */
?>

<tbody>
<?php foreach ($contacts as $contact): ?>
    <tr>
        <td>
            <a href="/contact/update/<?= $contact->id ?>">Изменить</a>
            <a class="js-delete-contact" href="#" data-id="<?= $contact->id ?>">Удалить</a>
        </td>
        <td>
            <img
                src="/<?= str_replace(ROOT_DIR, '', $contact->photo) ?>"
                alt="<?= $contact->getLabel('photo') ?>"
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
