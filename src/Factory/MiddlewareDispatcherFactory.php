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
use Yiisoft\Csrf\CsrfMiddleware;
use Yiisoft\Yii\Web\Middleware\SubFolder;
use Yiisoft\Yii\Web\MiddlewareDispatcher;
use Yiisoft\Session\SessionMiddleware;

class MiddlewareDispatcherFactory
{
    /**
     * @var array
     */
    private array $middlewares;

    /**
     * @param array $middlewares
     */
    public function __construct(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @param ContainerInterface $container
     * @return MiddlewareDispatcher
     */
    public function __invoke(ContainerInterface $container): MiddlewareDispatcher
    {
        $csrf = $container->get(CsrfMiddleware::class);
        $session = $container->get(SessionMiddleware::class);
        $router = $container->get(Router::class);
        $errorCatcher = $container->get(ErrorCatcher::class);
        $subFolder = $container->get(SubFolder::class);

        $dispatcher = (new MiddlewareDispatcher($container))
            ->addMiddleware($router)
            ->addMiddleware($subFolder)
            ->addMiddleware($session)
            ->addMiddleware($csrf)
        ;

        foreach ($this->middlewares as $middleware) {
            $dispatcher->addMiddleware($container->get($middleware));
        }

        $dispatcher->addMiddleware($errorCatcher);

        return $dispatcher;
    }
}
