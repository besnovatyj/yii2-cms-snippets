<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

use Besnovatyj\Backend\Widgets\grid\ActionColumn;
use Besnovatyj\Backend\Widgets\pagination\LinkPager;
use Besnovatyj\Kernel\security\AccessHelper;
use Besnovatyj\Snippets\entities\Snippet;
use Besnovatyj\Snippets\forms\search\SnippetSearch;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SnippetSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $groups array<int,string> */

$this->title = 'Сниппеты';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Создать сниппет', ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Категории', ['group/index'], ['class' => 'btn btn-outline-secondary']) ?>
</p>

<div class="container-fluid">
    <div class="card">
        <div class="card-header"><?= $this->title ?></div>
        <div class="card-body table-responsive">
            <?= GridView::widget([
                'options' => ['class' => 'table detail-view'],
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{summary}\n{items}",
                'columns' => [
                    [
                        'attribute' => 'title',
                        'value' => static function (Snippet $model) {
                            return Html::a(Html::encode($model->title), ['update', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'group_id',
                        'value' => static function (Snippet $model) use ($groups) {
                            return $model->group_id !== null ? ($groups[$model->group_id] ?? '—') : 'Без категории';
                        },
                        'filter' => $groups,
                    ],
                    'keywords',
                    'sort_order',
                    [
                        'class' => ActionColumn::class,
                        'template' => AccessHelper::filterActionColumn(['update', 'delete']),
                    ],
                ],
            ]) ?>
        </div>
        <div class="card-footer clearfix">
            <nav aria-label="" class="nav-pagination">
                <?= LinkPager::widget([
                    'pagination' => $dataProvider->getPagination(),
                ]) ?>
            </nav>
        </div>
    </div>
</div>
