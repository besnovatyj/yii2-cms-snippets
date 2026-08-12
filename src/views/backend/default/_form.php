<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Editor\EditorWidget;
use Besnovatyj\Snippets\forms\backend\SnippetForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model SnippetForm */
/* @var $groups array<int,string> */

?>
<?php $form = ActiveForm::begin(); ?>
<div class="card">
    <div class="card-header">Сниппет</div>
    <div class="card-body">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'group_id')->dropDownList($groups, ['prompt' => 'Без категории']) ?>

        <?= $form->field($model, 'html')->widget(EditorWidget::class, [
            'height' => 300,
        ]) ?>

        <?= $form->field($model, 'preview')->textarea(['rows' => 3])
            ->hint('Необязательно. Если пусто — превью в пикере строится из тела.') ?>
        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true])
            ->hint('Через запятую — для поиска в пикере, помимо заголовка.') ?>
        <?= $form->field($model, 'sort_order')->textInput() ?>
    </div>
    <div class="card-footer">
        <div class="d-grid">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
