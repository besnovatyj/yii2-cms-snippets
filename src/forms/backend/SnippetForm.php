<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\forms\backend;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Snippets\entities\Snippet;

/**
 * Форма создания/редактирования сниппета.
 *
 * Наследует {@see BaseForm}: скаляры из POST приводятся к typed-свойствам (нет TypeError).
 */
class SnippetForm extends BaseForm
{
    public ?int $group_id = null;
    public string $title = '';
    public string $html = '';
    public ?string $preview = null;
    public ?string $keywords = null;
    public int $sort_order = 0;

    public function __construct(?Snippet $snippet = null, array $config = [])
    {
        if ($snippet) {
            $this->group_id = $snippet->group_id;
            $this->title = $snippet->title;
            $this->html = $snippet->html;
            $this->preview = $snippet->preview;
            $this->keywords = $snippet->keywords;
            $this->sort_order = $snippet->sort_order;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'html'], 'required'],
            [['group_id', 'sort_order'], 'integer'],
            ['group_id', 'default', 'value' => null],
            [['html', 'preview'], 'string'],
            ['title', 'string', 'max' => 255],
            ['keywords', 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'group_id' => 'Категория',
            'title' => 'Заголовок',
            'html' => 'Тело (HTML)',
            'preview' => 'Превью (необязательно)',
            'keywords' => 'Ключевые слова (через запятую)',
            'sort_order' => 'Порядок',
        ];
    }
}
