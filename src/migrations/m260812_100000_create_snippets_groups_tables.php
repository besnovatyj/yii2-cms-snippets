<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use yii\base\NotSupportedException;

class m260812_100000_create_snippets_groups_tables extends BaseMigration
{
    public const string TABLE_NAME = '{{%snippet_groups}}';

    /**
     * @throws NotSupportedException
     */
    public function safeUp(): void
    {
        parent::safeUp();

        if (!$this->existTable(static::TABLE_NAME)) {
            $this->createTable(static::TABLE_NAME, [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull()
                    ->comment('Подпись категории'),
                'sort_order' => $this->integer()->notNull()->defaultValue(0)
                    ->comment('Порядок среди групп'),
            ], $this->tableOptions);
            $this->addCommentOnTable(static::TABLE_NAME, 'Категории сниппетов');

            $this->createIndexes(static::TABLE_NAME, 'sort_order');
        }

        parent::safeUp();
    }

}
