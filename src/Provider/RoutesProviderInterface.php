<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web\Provider;

use Psr\Container\ContainerInterface;

interface RoutesProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @return array
     */
    public function getRoutes(ContainerInterface $container): array;
}
