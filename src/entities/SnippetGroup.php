<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Категория сниппетов (один уровень группировки для пикера редактора).
 *
 * @property int    $id
 * @property string $name       Подпись категории.
 * @property int    $sort_order Порядок среди других групп (меньше — выше).
 *
 * @property-read Snippet[] $snippets
 */
class SnippetGroup extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%snippet_groups}}';
    }

    public static function create(string $name, int $sortOrder = 0): self
    {
        $entity = new static();
        $entity->name = $name;
        $entity->sort_order = $sortOrder;
        return $entity;
    }

    public function edit(string $name, int $sortOrder): void
    {
        $this->name = $name;
        $this->sort_order = $sortOrder;
    }

    public function getSnippets(): ActiveQuery
    {
        return $this->hasMany(Snippet::class, ['group_id' => 'id']);
    }
}
