<?php

namespace Mailery\Web\Factory;

use Mailery\Web\View\WebView;
use Amlsoft\Web\Enum\SettingKey;
use Amlsoft\Settings\Service\SettingRegistryInterface;
use Mailery\Menu\Sidebar\SidebarMenuInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Assets\AssetManager;
use Yiisoft\View\Theme;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\Web\User\User;
use Yiisoft\Widget\WidgetFactory;

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
//            'user' => $container->get(User::class)->getIdentity(),
            'assetManager' => $container->get(AssetManager::class),
            'urlGenerator' => $container->get(UrlGeneratorInterface::class),
            'sidebarMenu' => $container->get(SidebarMenuInterface::class),
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
