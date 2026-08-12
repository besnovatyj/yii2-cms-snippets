<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\controllers\backend;

use Besnovatyj\Contracts\snippet\Snippet;
use Besnovatyj\Contracts\snippet\SnippetGroup;
use Besnovatyj\Snippets\services\SnippetCatalog;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * JSON-эндпоинт дерева сниппетов для фронтового пикера (npm-ядро `@besnovatyj/snippets-core`).
 *
 * Отдаёт агрегированное {@see SnippetCatalog::groups()} дерево в форме, зеркалящей TS-контракт
 * ядра (`SnippetTreeDto`). Только чтение (GET), поэтому CSRF не требуется.
 */
class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function __construct(
        $id,
        $module,
        private readonly SnippetCatalog $catalog,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'only' => ['tree'],
                'formats' => ['application/json' => Response::FORMAT_JSON],
            ],
        ];
    }

    /**
     * Дерево сниппетов: `{ groups: [{ id, label, items: [{ id, title, html, preview, keywords }] }] }`.
     *
     * @return array{groups: array<int, array<string, mixed>>}
     */
    public function actionTree(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'groups' => array_map($this->groupToArray(...), $this->catalog->groups()),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function groupToArray(SnippetGroup $group): array
    {
        return [
            'id' => $group->id,
            'label' => $group->label,
            'items' => array_map($this->snippetToArray(...), $group->items),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function snippetToArray(Snippet $snippet): array
    {
        return [
            'id' => $snippet->id,
            'title' => $snippet->title,
            'html' => $snippet->html,
            'preview' => $snippet->preview,
            'keywords' => $snippet->keywords,
        ];
    }
}
