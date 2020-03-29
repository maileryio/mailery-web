<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;

abstract class Controller implements ViewContextInterface
{
    /**
     * @var Aliases
     */
    private Aliases $aliases;

    /**
     * @var WebView
     */
    private WebView $view;

    /**
     * @var string
     */
    private string $layout = 'main';

    /**
     * @var string
     */
    private string $baseViewPath = '@views';

    /**
     * @var string
     */
    private string $baseLayoutPath = '@views/layout';

    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param Aliases $aliases
     * @param WebView $view
     */
    public function __construct(ResponseFactoryInterface $responseFactory, Aliases $aliases, WebView $view)
    {
        $this->responseFactory = $responseFactory;
        $this->aliases = $aliases;
        $this->view = $view;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        $shortName = (new \ReflectionClass($this))->getShortName();
        return strtolower(preg_replace('/Controller$/', '', $shortName));
    }

    /**
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->getBaseViewPath() . '/' . $this->getId();
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout(string $layout): string
    {
        return $this->layout = $layout;
    }

    /**
     * @return string
     */
    public function getBaseViewPath(): string
    {
        return (string) $this->aliases->get($this->baseViewPath);
    }

    /**
     * @param string $baseViewPath
     */
    public function setBaseViewPath(string $baseViewPath)
    {
        $this->baseViewPath = $baseViewPath;
    }

    /**
     * @return string
     */
    public function getBaseLayoutPath(): string
    {
        return (string) $this->aliases->get($this->baseLayoutPath);
    }

    /**
     * @param string $baseLayoutPath
     */
    public function setBaseLayoutPath(string $baseLayoutPath)
    {
        $this->baseLayoutPath = $baseLayoutPath;
    }

    /**
     * @return ResponseFactoryInterface
     */
    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return ResponseInterface
     */
    protected function render(string $view, array $parameters = []): ResponseInterface
    {
        $response = $this->getResponseFactory()->createResponse();
        $content = $this->view->render($view, $parameters, $this);
        $response->getBody()->write($this->renderContent($content));

        return $response;
    }

    /**
     * @param string $url
     * @param int $status
     * @return ResponseInterface
     */
    protected function redirect(string $url, int $status = 302): ResponseInterface
    {
        return $this->getResponseFactory()->createResponse()
            ->withStatus($status)
            ->withHeader('Location', $url);
    }

    /**
     * @param array $data
     * @return ResponseInterface
     */
    protected function asJson(array $data = []): ResponseInterface
    {
        $response = $this->getResponseFactory()
            ->createResponse()
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($data));

        return $response;
    }

    /**
     * @param string $content
     * @return string
     */
    private function renderContent(string $content): string
    {
        $layout = $this->findLayoutFile($this->getBaseLayoutPath() . '/' . $this->getLayout());
        if ($layout !== null) {
            return $this->view->renderFile(
                $layout,
                [
                    'content' => $content,
                ],
                $this
            );
        }

        return $content;
    }

    /**
     * @param string|null $file
     * @return string|null
     */
    private function findLayoutFile(?string $file): ?string
    {
        if ($file === null) {
            return null;
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }

        return $file . '.' . $this->view->getDefaultExtension();
    }
}
