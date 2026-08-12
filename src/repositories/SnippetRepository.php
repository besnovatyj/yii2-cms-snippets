<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\repositories;

use Besnovatyj\Snippets\entities\Snippet;
use RuntimeException;
use Throwable;
use yii\db\Exception;
use yii\db\StaleObjectException;

/**
 * CRUD-репозиторий сниппетов.
 */
class SnippetRepository
{
    public function get(int $id): Snippet
    {
        if (!$entity = Snippet::findOne($id)) {
            throw new NotFoundException('Snippet is not found.');
        }
        return $entity;
    }

    /**
     * @throws Exception
     */
    public function save(Snippet $entity): void
    {
        if (!$entity->save()) {
            throw new RuntimeException('Snippet saving error.');
        }
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function remove(Snippet $entity): void
    {
        if (!$entity->delete()) {
            throw new RuntimeException('Snippet removing error.');
        }
    }
}
