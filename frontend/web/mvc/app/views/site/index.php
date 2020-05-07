<?php

use core\Auth;
use core\Helper;
use core\View;

/**
 * @var $this View
 * @var $totalPages int
 */

$isAuth = Auth::getInstance()->has();

?>

<h1>Todo</h1>

<div class="row">
    <div class="col-9">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">
                    <?= Helper::getSortLink('ID', 'id') ?>
                </th>
                <th scope="col">
                    <?= Helper::getSortLink('Name', 'userName') ?>
                </th>
                <th scope="col">
                    <?= Helper::getSortLink('Email', 'email') ?>
                </th>
                <th scope="col">Text</th>
                <th scope="col">
                    <?= Helper::getSortLink('Status', 'isDone') ?>
                </th>
                <?php if($isAuth): ?>
                    <th scope="col">Action</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $i => $datum): ?>
                <tr>
                    <th scope="row"><?= $datum['id'] ?></th>
                    <td><?= htmlentities($datum['userName']) ?></td>
                    <td><?= htmlentities($datum['email']) ?></td>
                    <td><?= htmlentities($datum['text']) ?></td>
                    <td><?= $datum['isDone'] ? 'Competed' : 'In work' ?></td>
                    <?php if($isAuth): ?>
                        <td>
                            <a href="?r=site/get&id=<?= $datum['id'] ?>">Edit</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?= $this->render('site/_paginator', [
            'totalPages' => $totalPages,
        ]) ?>

    </div>
    <div class="col 3">
        <?= $this->render('site/_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

