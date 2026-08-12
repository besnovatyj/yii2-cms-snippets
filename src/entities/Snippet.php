<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Сниппет — заготовленный кусок HTML/текста, вставляемый в контент редактора «как есть».
 *
 * @property int         $id
 * @property int|null    $group_id   Категория {@see SnippetGroup} (null — «Без категории»).
 * @property string      $title      Подпись для списка/поиска в пикере.
 * @property string      $html       Тело, вставляемое в контент редактора.
 * @property string|null $preview    HTML/текст превью (null — превью строится из {@see $html}).
 * @property string|null $keywords   Ключевые слова для поиска, через запятую.
 * @property int         $sort_order Порядок внутри группы (меньше — выше).
 *
 * @property-read SnippetGroup|null $group
 */
class Snippet extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%snippet_snippets}}';
    }

    public static function create(
        ?int $groupId,
        string $title,
        string $html,
        ?string $preview,
        ?string $keywords,
        int $sortOrder = 0,
    ): self {
        $entity = new static();
        $entity->group_id = $groupId;
        $entity->title = $title;
        $entity->html = $html;
        $entity->preview = $preview;
        $entity->keywords = $keywords;
        $entity->sort_order = $sortOrder;
        return $entity;
    }

    public function edit(
        ?int $groupId,
        string $title,
        string $html,
        ?string $preview,
        ?string $keywords,
        int $sortOrder,
    ): void {
        $this->group_id = $groupId;
        $this->title = $title;
        $this->html = $html;
        $this->preview = $preview;
        $this->keywords = $keywords;
        $this->sort_order = $sortOrder;
    }

    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(SnippetGroup::class, ['id' => 'group_id']);
    }

    /**
     * Ключевые слова как массив (пустые элементы отброшены).
     *
     * @return string[]
     */
    public function keywordList(): array
    {
        if ($this->keywords === null || $this->keywords === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->keywords))));
    }
}
