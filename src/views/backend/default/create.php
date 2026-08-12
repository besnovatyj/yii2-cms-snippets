<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Snippets\forms\backend\SnippetForm;
use yii\web\View;

/* @var $this View */
/* @var $model SnippetForm */
/* @var $groups array<int,string> */

$this->title = 'Новый сниппет';
$this->params['breadcrumbs'][] = ['label' => 'Сниппеты', 'url' => ['backend/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <?= $this->render('_form', ['model' => $model, 'groups' => $groups]) ?>
</div>
