<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets;

use Besnovatyj\Contracts\module\DeclaresModule;
use Besnovatyj\Contracts\module\ProvidesAdminMenu;
use Besnovatyj\Contracts\module\ProvidesComponents;
use Besnovatyj\Contracts\module\ProvidesMigrations;
use Besnovatyj\Contracts\snippet\SnippetGroup;
use Besnovatyj\Contracts\snippet\SnippetProvider;
use Besnovatyj\Kernel\module\CmsModule;
use Besnovatyj\Snippets\providers\DbSnippetProvider;
use Besnovatyj\Snippets\services\SnippetCatalog;

/**
 * Модуль сниппетов — заготовленных кусков HTML/текста для вставки в WYSIWYG-редактор.
 *
 * Реализует {@see SnippetProvider}, отдавая собственные (хранимые в БД) сниппеты через
 * {@see DbSnippetProvider}. Благодаря этому данные модуля попадают в общее дерево ровно тем же
 * `instanceof`-сканом {@see SnippetCatalog}, что и вклад любого другого модуля-провайдера —
 * никакой особой ветки для «своих» сниппетов не требуется (единообразие, как у модуля меню).
 */
class Module extends CmsModule implements
    DeclaresModule,
    ProvidesComponents,
    ProvidesMigrations,
    ProvidesAdminMenu,
    SnippetProvider
{
    public const bool EDITABLE = true;
    public const string VERSION = '1.0.0';
    public const string MODULE_ID = 'Snippets';

    public static function moduleId(): string { return self::MODULE_ID; }
    public static function moduleVersion(): string { return self::VERSION; }
    public static function isEditable(): bool { return self::EDITABLE; }
    public static function adminMenu(): array { return require __DIR__ . '/config/adminMenu.php'; }
    public static function moduleConfig(): array { return require __DIR__ . '/config/config.php'; }
    public static function migrationPath(): string { return __DIR__ . '/migrations'; }
    public static function migrationNamespace(): ?string { return __NAMESPACE__ . '\\migrations'; }

    public static function components(): array
    {
        return [
            'snippetCatalog' => ['class' => SnippetCatalog::class],
        ];
    }

    /**
     * Собственные сниппеты модуля (из БД) как вклад в общее дерево.
     *
     * @return SnippetGroup[]
     */
    public function snippetGroups(): array
    {
        return (new DbSnippetProvider())->snippetGroups();
    }
}
