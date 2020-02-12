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
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Yii\Web\ErrorHandler\ErrorCatcher;
use Yiisoft\Yii\Web\MiddlewareDispatcher;
use Yiisoft\Yii\Web\Session\SessionMiddleware;

class MiddlewareDispatcherFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $session = $container->get(SessionMiddleware::class);
        $router = $container->get(Router::class);
        $errorCatcher = $container->get(ErrorCatcher::class);

        return new MiddlewareDispatcher([
            $errorCatcher,
            $session,
            $router,
        ], $container);
    }
}
