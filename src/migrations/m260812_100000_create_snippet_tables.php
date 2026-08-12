<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

/**
 * Таблицы модуля сниппетов: категории и сами сниппеты.
 *
 * Install-only (модульные миграции применяются установщиком modman): правим этот файл,
 * а не добавляем alter-миграции.
 */
class m260812_100000_create_snippet_tables extends BaseMigration
{
    public const string GROUPS_TABLE = '{{%snippet_groups}}';
    public const string SNIPPETS_TABLE = '{{%snippet_snippets}}';

    /**
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        parent::safeUp();

        if (!$this->existTable(static::GROUPS_TABLE)) {
            $this->createTable(static::GROUPS_TABLE, [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull()
                    ->comment('Подпись категории'),
                'sort_order' => $this->integer()->notNull()->defaultValue(0)
                    ->comment('Порядок среди групп'),
            ], $this->tableOptions);
            $this->addCommentOnTable(static::GROUPS_TABLE, 'Категории сниппетов');

            $this->createIndexes(static::GROUPS_TABLE, 'sort_order');
        }

        if (!$this->existTable(static::SNIPPETS_TABLE)) {
            $this->createTable(static::SNIPPETS_TABLE, [
                'id' => $this->primaryKey(),
                'group_id' => $this->integer()->null()
                    ->comment('Категория (null — без категории)'),
                'title' => $this->string(255)->notNull()
                    ->comment('Подпись для списка/поиска'),
                'html' => $this->text()->notNull()
                    ->comment('Тело, вставляемое в контент'),
                'preview' => $this->text()->null()
                    ->comment('Превью для пикера (null — из html)'),
                'keywords' => $this->string(255)->null()
                    ->comment('Ключевые слова для поиска, через запятую'),
                'sort_order' => $this->integer()->notNull()->defaultValue(0)
                    ->comment('Порядок внутри группы'),
            ], $this->tableOptions);
            $this->addCommentOnTable(static::SNIPPETS_TABLE, 'Сниппеты');

            $this->createIndexes(static::SNIPPETS_TABLE, ['group_id', 'sort_order']);
            $this->createFKs(
                static::SNIPPETS_TABLE,
                'group_id',
                static::GROUPS_TABLE,
                'id',
                'SET NULL',
                'CASCADE',
            );
        }

        parent::safeUp();
    }

    public function safeDown(): void
    {
        parent::safeDown();
    }
}
