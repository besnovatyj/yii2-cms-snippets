<?php

/*
 * Copyright (c) 2026 Besnovatyj. Licensed under the MIT License.
 */

return [[
    'label' => 'Сниппеты',
    'iconClass' => 'bi bi-file-earmark-code me-1',
    'url' => ['/Snippets/backend/default/index'],
    'active' => static function () {
        return str_contains(\Yii::$app->request->url, 'Snippets/backend');
    },
    '_meta' => [
        'placements' => [
            [
                'location' => 'right-sidebar',
                'group' => 'Content',
                'groupIcon' => 'bi bi-pencil-square',
                'priority' => 100,
                'groupPriority' => 90,
            ],
        ],
    ],
]];
