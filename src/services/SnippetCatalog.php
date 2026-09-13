<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\services;

use Besnovatyj\Contracts\snippet\SnippetGroup;
use Besnovatyj\Contracts\snippet\SnippetProvider;
use Besnovatyj\Kernel\module\ModuleFinder;
use yii\base\Component;

/**
 * Агрегатор сниппетов: собирает дерево из всех подключённых источников {@see SnippetProvider}.
 *
 * Находит провайдеров сканом подключённых Yii-модулей по контракту `SnippetProvider` ({@see ModuleFinder},
 * инстанцируются только провайдеры) — ровно как модуль меню находит
 * {@see \Besnovatyj\Contracts\menu\MenuTargetProvider}. Группы с одинаковым `id` от разных провайдеров сливаются (сниппеты складываются, sort берётся минимальный), поэтому модули
 * могут дополнять общие категории, не зная друг о друге. Результат отдаётся фронту API-эндпоинтом.
 */
class SnippetCatalog extends Component
{
    /**
     * Единое отсортированное дерево сниппетов от всех провайдеров.
     *
     * @return SnippetGroup[]
     */
    public function groups(): array
    {
        /** @var array<string, SnippetGroup> $merged */
        $merged = [];

        foreach ($this->providers() as $provider) {
            foreach ($provider->snippetGroups() as $group) {
                if (!isset($merged[$group->id])) {
                    $merged[$group->id] = $group;
                    continue;
                }

                $existing = $merged[$group->id];
                $merged[$group->id] = new SnippetGroup(
                    id: $existing->id,
                    label: $existing->label,
                    items: [...$existing->items, ...$group->items],
                    sort: min($existing->sort, $group->sort),
                );
            }
        }

        $groups = array_values($merged);
        usort($groups, static fn (SnippetGroup $a, SnippetGroup $b) => [$a->sort, $a->label] <=> [$b->sort, $b->label]);

        return $groups;
    }

    /**
     * Подключённые модули, реализующие контракт провайдера сниппетов.
     *
     * @return SnippetProvider[]
     */
    private function providers(): array
    {
        return array_values(ModuleFinder::implementing(SnippetProvider::class));
    }
}
