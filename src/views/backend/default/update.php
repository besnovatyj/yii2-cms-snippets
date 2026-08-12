<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Snippets\entities\Snippet;
use Besnovatyj\Snippets\forms\backend\SnippetForm;
use yii\web\View;

/* @var $this View */
/* @var $model SnippetForm */
/* @var $snippet Snippet */
/* @var $groups array<int,string> */

$this->title = 'Сниппет: ' . $snippet->title;
$this->params['breadcrumbs'][] = ['label' => 'Сниппеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="container-fluid">
    <?= $this->render('_form', ['model' => $model, 'groups' => $groups]) ?>
</div>
