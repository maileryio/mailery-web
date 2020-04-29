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

use Mailery\Brand\Contract\BrandLocatorInterface;
use Mailery\Menu\Navbar\NavbarMenuInterface;
use Mailery\Menu\Sidebar\SidebarMenuInterface;
use Mailery\Web\View\WebView;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\I18n\TranslatorInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\Theme;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Yii\Web\User\User;

class ViewFactory
{
    /**
     * @param ContainerInterface $container
     * @return WebView
     */
    public function __invoke(ContainerInterface $container): WebView
    {
        WidgetFactory::initialize($container);

        $aliases = $container->get(Aliases::class);
        $theme = $container->get(Theme::class);
        $logger = $container->get(LoggerInterface::class);
        $eventDispatcher = $container->get(EventDispatcherInterface::class);
//        $settingRegistry = $container->get(SettingRegistryInterface::class);

        $webView = new WebView($aliases->get('@views'), $theme, $eventDispatcher, $logger);
        $webView->setDefaultParameters([
            'aliases' => $aliases,
            'request' => $container->get(ServerRequestInterface::class),
            'translator' => $container->get(TranslatorInterface::class),
            'user' => $container->get(User::class)->getIdentity(),
            'assetManager' => $container->get(AssetManager::class),
            'urlGenerator' => $container->get(UrlGeneratorInterface::class),
            'navbarMenu' => $container->get(NavbarMenuInterface::class),
            'sidebarMenu' => $container->get(SidebarMenuInterface::class),
            'brandLocator' => $container->get(BrandLocatorInterface::class),
        ]);

        $webView->setTitle('Default');
        $webView->setLanguage('ru-RU');
        $webView->setEncoding('utf-8');

//        $webView->setTitle($settingRegistry->get(SettingKey::GROUP_NAME, SettingKey::PARAM_APP_NAME)->getValue());
//        $webView->setLanguage($settingRegistry->get(SettingKey::GROUP_NAME, SettingKey::PARAM_APP_LANGUAGE)->getValue());
//        $webView->setEncoding($settingRegistry->get(SettingKey::GROUP_NAME, SettingKey::PARAM_APP_ENCODING)->getValue());

        return $webView;
    }
}
