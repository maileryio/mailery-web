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

class VueAssetBundle extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public ?string $basePath = '@public/assets';

    /**
     * {@inheritdoc}
     */
    public ?string $baseUrl = '@assetsUrl';

    /**
     * {@inheritdoc}
     */
    public ?string $sourcePath = '@npm/vue/dist';

    /**
     * {@inheritdoc}
     */
    public array $js = [
        'vue.min.js',
    ];
}
