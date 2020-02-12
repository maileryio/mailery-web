<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web\Factory;

use Psr\Container\ContainerInterface;
use Yiisoft\Router\FastRoute\FastRouteFactory;
use Yiisoft\Router\RouterFactory;
use Yiisoft\Router\RouterInterface;

class AppRouterFactory
{
    /**
     * @param ContainerInterface $container
     * @return RouterInterface
     */
    public function __invoke(ContainerInterface $container): RouterInterface
    {
        /** @var RoutesProviderFactory $routesProvider */
        $routesProvider = $container->get(RoutesProviderFactory::class);
        $routes = $routesProvider($container);

        return (new RouterFactory(new FastRouteFactory(), $routes))($container);
    }
}
