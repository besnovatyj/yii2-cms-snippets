<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\migrations;

use Besnovatyj\Kernel\migration\BaseMigration;
use Yii;
use yii\db\Exception;

class m260812_100020_create_snippets_foreign_key_constraints extends BaseMigration
{
    /**
     * @throws Exception
     */
    public function safeUp(): void
    {
        parent::safeUp();

        Yii::$app->getDb()->createCommand('SET foreign_key_checks = 0')->execute();

        $this->createFKs(
            m260812_100010_create_snippets_snippets_tables::TABLE_NAME,
            'group_id',
            m260812_100000_create_snippets_groups_tables::TABLE_NAME,
            'id',
            'SET NULL',
            'CASCADE',
        );

        Yii::$app->getDb()->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * Переопределяем пустым методом, так как BaseMigration::safeDown() вызывает static::TABLE_NAME,
     * которого в данной миграции нет. Удаление FK произойдёт автоматически при дропе таблиц
     * при переустановке модуля.
     */
    public function safeDown(): void
    {
        // empty
    }
}
