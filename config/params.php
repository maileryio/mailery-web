<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Web\Assets\AppAssetBundle;
use Mailery\Web\Assets\VueAssetBundle;
use Mailery\Web\Assets\VuexAssetBundle;
use Mailery\Web\Assets\BootstrapVueAssetBundle;

return [
    'assetManager' => [
        'bundles' => [
            AppAssetBundle::class => [
                'depends' => [
                    VueAssetBundle::class,
                    VuexAssetBundle::class,
                    BootstrapVueAssetBundle::class,
                ],
            ],
        ],
        'publisher' => [
            'forceCopy' => false,
            'appendTimestamp' => true,
        ],
    ],

    'router' => [
        'routes' => [],
    ],
];
