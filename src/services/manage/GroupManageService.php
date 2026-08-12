<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\services\manage;

use Besnovatyj\Snippets\entities\SnippetGroup;
use Besnovatyj\Snippets\forms\backend\GroupForm;
use Besnovatyj\Snippets\repositories\GroupRepository;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * Сервис CRUD категорий сниппетов.
 */
class GroupManageService
{
    public function __construct(private readonly GroupRepository $repo)
    {
    }

    /**
     * @throws Exception
     */
    public function create(GroupForm $form): SnippetGroup
    {
        $entity = SnippetGroup::create($form->name, $form->sort_order);
        $this->repo->save($entity);
        return $entity;
    }

    /**
     * @throws Exception
     */
    public function edit(int $id, GroupForm $form): void
    {
        $entity = $this->repo->get($id);
        $entity->edit($form->name, $form->sort_order);
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
