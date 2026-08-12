<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\services\manage;

use Besnovatyj\Snippets\entities\Snippet;
use Besnovatyj\Snippets\forms\backend\SnippetForm;
use Besnovatyj\Snippets\repositories\SnippetRepository;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * Сервис CRUD сниппетов.
 */
class SnippetManageService
{
    public function __construct(private readonly SnippetRepository $repo)
    {
    }

    /**
     * @throws Exception
     */
    public function create(SnippetForm $form): Snippet
    {
        $entity = Snippet::create(
            $form->group_id,
            $form->title,
            $form->html,
            $form->preview,
            $form->keywords,
            $form->sort_order,
        );
        $this->repo->save($entity);
        return $entity;
    }

    /**
     * @throws Exception
     */
    public function edit(int $id, SnippetForm $form): void
    {
        $entity = $this->repo->get($id);
        $entity->edit(
            $form->group_id,
            $form->title,
            $form->html,
            $form->preview,
            $form->keywords,
            $form->sort_order,
        );
        $this->repo->save($entity);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(int $id): void
    {
        $entity = $this->repo->get($id);
        $this->repo->remove($entity);
    }
}
