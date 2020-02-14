<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Web\Emitter\SapiEmitter;
use Mailery\Web\Factory\AppRouterFactory;
use Mailery\Web\Factory\MiddlewareDispatcherFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Router\RouterInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\Web\Emitter\EmitterInterface;
use Yiisoft\Yii\Web\MiddlewareDispatcher;
use Yiisoft\Yii\Web\ServerRequestFactory;
use Yiisoft\Yii\Web\Session\Session;
use Yiisoft\Yii\Web\Session\SessionInterface;
use Yiisoft\View\WebView;
use Mailery\Web\Factory\ViewFactory;

return [
    // PSR-17 factories:
    RequestFactoryInterface::class => Psr17Factory::class,
    ServerRequestFactoryInterface::class => Psr17Factory::class,
    ResponseFactoryInterface::class => Psr17Factory::class,
    StreamFactoryInterface::class => Psr17Factory::class,
    UriFactoryInterface::class => Psr17Factory::class,
    UploadedFileFactoryInterface::class => Psr17Factory::class,
    ServerRequestInterface::class => function (ContainerInterface $container) {
        return $container->get(ServerRequestFactory::class)->createFromGlobals();
    },

    // custom stuff
    EmitterInterface::class => SapiEmitter::class,
    RouterInterface::class => new AppRouterFactory(),
    UrlMatcherInterface::class => Reference::to(RouterInterface::class),
    UrlGeneratorInterface::class => Reference::to(RouterInterface::class),
    MiddlewareDispatcher::class => new MiddlewareDispatcherFactory(),

    SessionInterface::class => [
        '__class' => Session::class,
        '__construct()' => [
            ['cookie_secure' => 0],
        ],
    ],

    // view
    WebView::class => new ViewFactory(),
];
