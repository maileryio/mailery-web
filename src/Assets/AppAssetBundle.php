<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web\Assets;

use Yiisoft\Assets\AssetBundle;

class AppAssetBundle extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public ?string $basePath = '@public/assets/@maileryio/mailery-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $baseUrl = '@assetsUrl/@maileryio/mailery-assets';

    /**
     * {@inheritdoc}
     */
    public ?string $sourcePath = '@npm/@maileryio/mailery-assets/dist';

    /**
     * {@inheritdoc}
     */
    public array $css = [
        '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&subset=cyrillic',
        'main.min.css',
    ];

    /**
     * {@inheritdoc}
     */
    public array $js = [
        'main.umd.min.js',
    ];

    /**
     * {@inheritdoc}
     */
    public array $depends = [
        VueAssetBundle::class,
        VuexAssetBundle::class,
        BootstrapVueAssetBundle::class,
    ];
}
