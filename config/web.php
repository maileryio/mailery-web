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
use Mailery\Web\Factory\ViewFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Yiisoft\Assets\AssetPublisher;
use Yiisoft\Assets\AssetPublisherInterface;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\GroupFactory;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Web\Emitter\EmitterInterface;
use Yiisoft\Yii\Web\MiddlewareDispatcher;
use Yiisoft\Yii\Web\ServerRequestFactory;
use Psr\Log\LoggerInterface;
use Yiisoft\Assets\AssetConverterInterface;
use Yiisoft\Assets\AssetManager;

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

    RouteCollectorInterface::class => new GroupFactory(),
    UrlMatcherInterface::class => new AppRouterFactory($params['router']['routes']),
    UrlGeneratorInterface::class => UrlGenerator::class,

    MiddlewareDispatcher::class => new MiddlewareDispatcherFactory(),

    AssetPublisherInterface::class => function (ContainerInterface $container) use($params) {
        $publisher = $container->get(AssetPublisher::class);
        $publisher->setForceCopy($params['assetManager']['publisher']['forceCopy']);
        $publisher->setAppendTimestamp($params['assetManager']['publisher']['appendTimestamp']);

        return $publisher;
    },

    AssetManager::class => function (ContainerInterface $container) use($params) {
        $assetManager = new AssetManager($container->get(LoggerInterface::class));

        $assetManager->setBundles($params['assetManager']['bundles']);
        $assetManager->setConverter($container->get(AssetConverterInterface::class));
        $assetManager->setPublisher($container->get(AssetPublisherInterface::class));

        return $assetManager;
    },

    // view
    WebView::class => new ViewFactory(),
];
