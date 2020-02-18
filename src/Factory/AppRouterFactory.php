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
use Yiisoft\Router\FastRoute\UrlMatcher;
use Yiisoft\Router\Group;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\UrlMatcherInterface;

class AppRouterFactory
{
    /**
     * @param ContainerInterface $container
     * @return UrlMatcherInterface
     */
    public function __invoke(ContainerInterface $container): UrlMatcherInterface
    {
        /** @var RoutesProviderFactory $routesProvider */
        $routesProvider = $container->get(RoutesProviderFactory::class);
        $routes = $routesProvider($container);

        $collector = $container->get(RouteCollectorInterface::class);
        $collector->addGroup(Group::create(null, $routes));

        return new UrlMatcher(new RouteCollection($collector));
    }
}
