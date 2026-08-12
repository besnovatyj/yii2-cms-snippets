<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\repositories;

use Besnovatyj\Snippets\entities\SnippetGroup;
use RuntimeException;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * CRUD-репозиторий категорий сниппетов.
 */
class GroupRepository
{
    public function get(int $id): SnippetGroup
    {
        if (!$entity = SnippetGroup::findOne($id)) {
            throw new NotFoundException('Snippet group is not found.');
        }
        return $entity;
    }

    /**
     * @throws Exception
     */
    public function save(SnippetGroup $entity): void
    {
        if (!$entity->save()) {
            throw new RuntimeException('Snippet group saving error.');
        }
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(SnippetGroup $entity): void
    {
        if (!$entity->delete()) {
            throw new RuntimeException('Snippet group removing error.');
        }
    }

    /**
     * Карта `id => name` для выпадающих списков (в порядке отображения).
     *
     * @return array<int,string>
     */
    public function labelMap(): array
    {
        $map = [];
        foreach (SnippetGroup::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all() as $group) {
            /** @var SnippetGroup $group */
            $map[$group->id] = $group->name;
        }
        return $map;
    }
}
