<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Kernel\security\AccessHelper;
use Besnovatyj\Snippets\entities\SnippetGroup;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Категории сниппетов';
$this->params['breadcrumbs'][] = ['label' => 'Сниппеты', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = 'Категории';
?>

<p>
    <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('К сниппетам', ['backend/default/index'], ['class' => 'btn btn-outline-secondary']) ?>
</p>

<div class="container-fluid">
    <div class="card">
        <div class="card-header"><?= $this->title ?></div>
        <div class="card-body table-responsive">
            <?= GridView::widget([
                'options' => ['class' => 'table detail-view'],
                'dataProvider' => $dataProvider,
                'layout' => "{summary}\n{items}",
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => static function (SnippetGroup $model) {
                            return Html::a(Html::encode($model->name), ['update', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'sort_order',
                    [
                        'class' => ActionColumn::class,
                        'template' => AccessHelper::filterActionColumn(['update', 'delete']),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
