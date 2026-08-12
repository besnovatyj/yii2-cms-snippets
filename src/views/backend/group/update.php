<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Snippets\entities\SnippetGroup;
use Besnovatyj\Snippets\forms\backend\GroupForm;
use yii\web\View;

/* @var $this View */
/* @var $model GroupForm */
/* @var $group SnippetGroup */

$this->title = 'Категория: ' . $group->name;
$this->params['breadcrumbs'][] = ['label' => 'Сниппеты', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="container-fluid">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
