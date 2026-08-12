<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\controllers\backend;

use Besnovatyj\Kernel\controller\ControllerTrait;
use Besnovatyj\Snippets\forms\backend\SnippetForm;
use Besnovatyj\Snippets\forms\search\SnippetSearch;
use Besnovatyj\Snippets\repositories\GroupRepository;
use Besnovatyj\Snippets\repositories\SnippetRepository;
use Besnovatyj\Snippets\services\manage\SnippetManageService;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Response;

/**
 * CRUD-админка сниппетов.
 */
class DefaultController extends Controller
{
    use ControllerTrait;

    public function __construct(
        $id,
        $module,
        private readonly SnippetManageService $service,
        private readonly SnippetRepository $repo,
        private readonly GroupRepository $groups,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $searchModel = new SnippetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groups' => $this->groups->labelMap(),
        ]);
    }

    public function actionCreate(): Response|string
    {
        $form = new SnippetForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $snippet = $this->service->create($form);
                return $this->redirect(['update', 'id' => $snippet->id]);
            } catch (Exception $e) {
                $this->handleDomainException($e, 'Ошибка');
            }
        }
        return $this->render('create', [
            'model' => $form,
            'groups' => $this->groups->labelMap(),
        ]);
    }

    public function actionUpdate(int $id): Response|string
    {
        $snippet = $this->repo->get($id);

        $form = new SnippetForm($snippet);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($snippet->id, $form);
                return $this->redirect(['update', 'id' => $snippet->id]);
            } catch (Exception $e) {
                $this->handleDomainException($e, 'Ошибка');
            }
        }
        return $this->render('update', [
            'model' => $form,
            'snippet' => $snippet,
            'groups' => $this->groups->labelMap(),
        ]);
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
