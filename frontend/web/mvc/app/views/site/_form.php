<?php

use app\models\Task;
use core\Auth;

/**
 * @var $model Task|null
 */

$url = $model
    ? "?r=site/store&id={$model->id}"
    : '?r=site/add';

?>
<form class="form-signin" action="<?= $url ?>" method="post">
    <h2 class="h3 mb-3 font-weight-normal">New task</h2>
    <label for="inputName" class="sr-only">Name</label>
    <input id="inputName" class="form-control" placeholder="Name" required autofocus
           name="userName" value="<?= $model ? $model->userName : '' ?>">
    <label for="inputEmail" class="sr-only">Email</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus
           name="email" value="<?= $model ? $model->email : '' ?>">
    <label for="inputText" class="sr-only">Text</label>
    <textarea name="text" id="inputText" class="form-control"><?= $model ? $model->text : '' ?></textarea>

    <?php if (Auth::getInstance()->has()): ?>
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me" name="isDone" <?= $model && $model->isDone ? 'checked' : '' ?>> Completed
            </label>
        </div>
    <?php endif; ?>
    <button class="btn btn-success btn-block" type="submit"><?= $model ? 'Edit' : 'Add' ?></button>
</form>

<style>
    .form-control {
        margin-bottom: 5px;
    }
</style>
