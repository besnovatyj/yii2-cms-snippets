<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

declare(strict_types=1);

namespace Besnovatyj\Snippets\forms\search;

use Besnovatyj\Forms\BaseForm;
use Besnovatyj\Snippets\entities\Snippet;
use yii\data\ActiveDataProvider;

/**
 * Поиск/фильтрация сниппетов для грида админки.
 */
class SnippetSearch extends BaseForm
{
    public ?int $id = null;
    public ?int $group_id = null;
    public ?string $title = null;
    public ?string $keywords = null;

    public function rules(): array
    {
        return [
            [['id', 'group_id'], 'integer'],
            [['title', 'keywords'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Snippet::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['sort_order' => SORT_ASC, 'id' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $this->group_id,
        ]);

        $query
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->keywords]);

        return $dataProvider;
    }
}
