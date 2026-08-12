<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Snippets\forms\backend\GroupForm;
use yii\web\View;

/* @var $this View */
/* @var $model GroupForm */

$this->title = 'Новая категория';
$this->params['breadcrumbs'][] = ['label' => 'Сниппеты', 'url' => ['backend/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
