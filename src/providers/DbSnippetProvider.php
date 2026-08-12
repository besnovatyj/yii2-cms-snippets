<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\providers;

use Besnovatyj\Contracts\snippet\Snippet as SnippetDto;
use Besnovatyj\Contracts\snippet\SnippetGroup as SnippetGroupDto;
use Besnovatyj\Contracts\snippet\SnippetProvider;
use Besnovatyj\Snippets\entities\Snippet;
use Besnovatyj\Snippets\entities\SnippetGroup;

/**
 * Базовый провайдер модуля: отдаёт сниппеты, которыми управляет контент-менеджер (из БД).
 *
 * Переводит AR-сущности в нейтральные контракт-DTO. Сниппеты без категории собираются в
 * синтетическую группу с пустым id, чтобы фронт получил однородное дерево.
 */
final class DbSnippetProvider implements SnippetProvider
{
    /** Id синтетической группы для сниппетов без категории. */
    private const string UNGROUPED_ID = '';

    public function snippetGroups(): array
    {
        $groups = SnippetGroup::find()
            ->with([
                'snippets' => static fn ($q) => $q->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]),
            ])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($groups as $group) {
            /** @var SnippetGroup $group */
            $result[] = new SnippetGroupDto(
                id: (string)$group->id,
                label: $group->name,
                items: array_map($this->toDto(...), $group->snippets),
                sort: $group->sort_order,
            );
        }

        $ungrouped = Snippet::find()
            ->where(['group_id' => null])
            ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        if ($ungrouped !== []) {
            $result[] = new SnippetGroupDto(
                id: self::UNGROUPED_ID,
                label: 'Без категории',
                items: array_map($this->toDto(...), $ungrouped),
                sort: PHP_INT_MAX,
            );
        }

        return $result;
    }

    private function toDto(Snippet $snippet): SnippetDto
    {
        return new SnippetDto(
            id: (string)$snippet->id,
            title: $snippet->title,
            html: $snippet->html,
            preview: $snippet->preview,
            keywords: $snippet->keywordList(),
        );
    }
}
