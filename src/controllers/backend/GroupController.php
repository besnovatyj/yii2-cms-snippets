<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\controllers\backend;

use Besnovatyj\Kernel\controller\ControllerTrait;
use Besnovatyj\Snippets\entities\SnippetGroup;
use Besnovatyj\Snippets\forms\backend\GroupForm;
use Besnovatyj\Snippets\repositories\GroupRepository;
use Besnovatyj\Snippets\services\manage\GroupManageService;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Response;

/**
 * CRUD-админка категорий сниппетов.
 */
class GroupController extends Controller
{
    use ControllerTrait;

    public function __construct(
        $id,
        $module,
        private readonly GroupManageService $service,
        private readonly GroupRepository $repo,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SnippetGroup::find(),
            'sort' => ['defaultOrder' => ['sort_order' => SORT_ASC, 'id' => SORT_ASC]],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate(): Response|string
    {
        $form = new GroupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->create($form);
                return $this->redirect(['index']);
            } catch (Exception $e) {
                $this->handleDomainException($e, 'Ошибка');
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    public function actionUpdate(int $id): Response|string
    {
        $group = $this->repo->get($id);

        $form = new GroupForm($group);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($group->id, $form);
                return $this->redirect(['index']);
            } catch (Exception $e) {
                $this->handleDomainException($e, 'Ошибка');
            }
        }
        return $this->render('update', ['model' => $form, 'group' => $group]);
    }

    public function actionDelete(int $id): Response
    {
        try {
            $this->service->remove($id);
        } catch (Throwable $e) {
            $this->handleDomainException($e, 'Ошибка');
        }
        return $this->redirect(['index']);
    }
}
