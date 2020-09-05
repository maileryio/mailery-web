<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Menu\MenuItem;

return [
    'assetManager' => [
        'publisher' => [
            'forceCopy' => false,
            'appendTimestamp' => true,
        ],
    ],

    'dispatcher' => [
        'middlewares' => [],
    ],

    'menu' => [
        'navbar' => [
            'items' => [
                'system' => (new MenuItem())
                    ->withLabel('System')
                    ->withOrder(100),
            ],
        ],
    ],
];
