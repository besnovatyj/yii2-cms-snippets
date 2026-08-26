<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260812_100010_create_snippets_snippets_tables extends BaseMigration
{
    public const string TABLE_NAME = '{{%snippet_snippets}}';

    /**
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        parent::safeUp();

        if (!$this->existTable(static::TABLE_NAME)) {
            $this->createTable(static::TABLE_NAME, [
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
            $this->addCommentOnTable(static::TABLE_NAME, 'Сниппеты');

            $this->createIndexes(static::TABLE_NAME, ['group_id', 'sort_order']);
        }

        parent::safeUp();
    }

}
