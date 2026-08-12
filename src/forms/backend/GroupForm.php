<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\forms\backend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Snippets\entities\SnippetGroup;

/**
 * Форма создания/редактирования категории сниппетов.
 */
class GroupForm extends BaseForm
{
    public string $name = '';
    public int $sort_order = 0;

    public function __construct(?SnippetGroup $group = null, array $config = [])
    {
        if ($group) {
            $this->name = $group->name;
            $this->sort_order = $group->sort_order;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 255],
            ['sort_order', 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'sort_order' => 'Порядок',
        ];
    }
}
