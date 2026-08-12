<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Snippets\forms\backend\GroupForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model GroupForm */

?>
<?php $form = ActiveForm::begin(); ?>
<div class="card">
    <div class="card-header">Категория</div>
    <div class="card-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'sort_order')->textInput() ?>
    </div>
    <div class="card-footer">
        <div class="d-grid">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
