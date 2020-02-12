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

use Mailery\Web\Provider\RoutesProviderInterface;
use Psr\Container\ContainerInterface;

class RoutesProviderFactory
{
    /**
     * @var array
     */
    private $providers;

    public function __construct(/* $providers */)
    {
        $this->providers = \func_get_args();
    }

    /**
     * @param ContainerInterface $container
     * @return array
     */
    public function __invoke(ContainerInterface $container): array
    {
        $routes = [];

        foreach ($this->providers as $provider) {
            if (!$provider instanceof RoutesProviderInterface) {
                $provider = $container->get($provider);
            }

            $routes = array_merge($routes, $provider->getRoutes($container));
        }

        return $routes;
    }
}
