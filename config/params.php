<?php

declare(strict_types=1);

return [
    'assetManager' => [
        'publisher' => [
            'forceCopy' => YII_ENV === 'dev',
            'appendTimestamp' => true,
        ],
    ],
];
