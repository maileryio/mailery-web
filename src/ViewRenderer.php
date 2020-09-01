<?php

namespace Mailery\Web;

use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;
use Yiisoft\Strings\Inflector;
use Yiisoft\Yii\Web\Middleware\Csrf;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\Yii\Web\User\User;

final class ViewRenderer implements ViewContextInterface
{
    /**
     * @var DataResponseFactoryInterface
     */
    private DataResponseFactoryInterface $responseFactory;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var Aliases
     */
    private Aliases $aliases;

    /**
     * @var WebView
     */
    private WebView $view;

    /**
     * @var UrlMatcherInterface
     */
    private UrlMatcherInterface $urlMatcher;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string
     */
    private string $layout = 'main';

    /**
     * @var string
     */
    private string $viewBasePath = '@views';

    /**
     * @var string|null
     */
    private ?string $viewPath = null;

    /**
     * @var string|null
     */
    private ?string $csrfToken = null;

    /**
     * @var string
     */
    private string $csrfTokenRequestAttribute;

    /**
     * @param DataResponseFactoryInterface $responseFactory
     * @param User $user
     * @param Aliases $aliases
     * @param WebView $view
     * @param UrlMatcherInterface $urlMatcher
     */
    public function __construct(
        DataResponseFactoryInterface $responseFactory,
        User $user,
        Aliases $aliases,
        WebView $view,
        UrlMatcherInterface $urlMatcher,
        string $layout
    ) {
        $this->responseFactory = $responseFactory;
        $this->user = $user;
        $this->aliases = $aliases;
        $this->view = $view;
        $this->urlMatcher = $urlMatcher;
        $this->layout = $layout;
    }

    public function getViewPath(): string
    {
        if ($this->viewPath !== null) {
            return $this->viewPath;
        }

        return $this->aliases->get($this->viewBasePath) . '/' . $this->name;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return ResponseInterface
     */
    public function render(string $view, array $parameters = []): ResponseInterface
    {
        $contentRenderer = fn () => $this->renderProxy($view, $parameters);

        return $this->responseFactory->createResponse($contentRenderer);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return ResponseInterface
     */
    public function renderPartial(string $view, array $parameters = []): ResponseInterface
    {
        $content = $this->view->render($view, $parameters, $this);

        return $this->responseFactory->createResponse($content);
    }

    /**
     * @param object $controller
     * @return self
     */
    public function withController(object $controller): self
    {
        $new = clone $this;
        $new->name = $this->getName($controller);
        $new->viewBasePath = $this->getViewBasePath($controller);

        return $new;
    }

    /**
     * @param string $name
     * @return self
     */
    public function withControllerName(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @param string $viewPath
     * @return self
     */
    public function withViewPath(string $viewPath): self
    {
        $new = clone $this;
        $new->viewPath = $viewPath;

        return $new;
    }

    /**
     * @param string $viewBasePath
     * @return self
     */
    public function withViewBasePath(string $viewBasePath): self
    {
        $new = clone $this;
        $new->viewBasePath = $viewBasePath;

        return $new;
    }

    /**
     * @param string $layout
     * @return self
     */
    public function withLayout(string $layout): self
    {
        $new = clone $this;
        $new->layout = $layout;

        return $new;
    }

    /**
     * @param string $requestAttribute
     * @return self
     */
    public function withCsrf(string $requestAttribute = Csrf::REQUEST_NAME): self
    {
        $new = clone $this;
        $new->csrfTokenRequestAttribute = $requestAttribute;
        $new->csrfToken = $new->getCsrfToken();

        return $new;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return string
     */
    private function renderProxy(string $view, array $parameters = []): string
    {
        if ($this->csrfToken !== null) {
            $parameters['csrf'] = $this->csrfToken;
            $this->view->registerMetaTag(
                [
                    'name' => 'csrf',
                    'content' => $this->csrfToken,
                ],
                'csrf_meta_tags'
            );
        }

        $content = $this->view->render($view, $parameters, $this);
        $user = $this->user->getIdentity();
        $layout = $this->findLayoutFile($this->aliases->get($this->layout));

        if ($layout === null) {
            return $content;
        }

        $layoutParameters['content'] = $content;
        $layoutParameters['user'] = $user;

        return $this->view->renderFile(
            $layout,
            $layoutParameters,
            $this,
        );
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

    /**
     * Returns the controller name. Name should be converted to "id" case.
     * Method returns classname without `controller` on the ending.
     * If namespace is not contain `controller` or `controllers`
     * then returns only classname without `controller` on the ending
     * else returns all subnamespaces from `controller` (or `controllers`) to the end
     *
     * @return string
     * @example App\Controller\FooBar\BazController -> foo-bar/baz
     * @example App\Controllers\FooBar\BazController -> foo-bar/baz
     * @example Path\To\File\BlogController -> blog
     * @see Inflector::camel2id()
     */
    private function getName(object $controller): string
    {
        if ($this->name !== null) {
            return $this->name;
        }

        $regexp = '/((?<=controller\\\|s\\\)(?:[\w\\\]+)|(?:[a-z]+))controller/iuU';
        if (!preg_match($regexp, get_class($controller), $m) || empty($m[1])) {
            throw new \RuntimeException('Cannot detect controller name');
        }

        $inflector = new Inflector();
        $name = str_replace('\\', '/', $m[1]);

        return $this->name = $inflector->pascalCaseToId($name);
    }

    /**
     * @param object $controller
     * @return string
     */
    private function getViewBasePath(object $controller): string
    {
        $fileName = (new \ReflectionClass($controller))->getFileName();
        return dirname(dirname(dirname($fileName))) . '/views';
    }

    /**
     * @return string
     */
    private function getCsrfToken(): string
    {
        return $this->urlMatcher->getLastMatchedRequest()->getAttribute($this->csrfTokenRequestAttribute);
    }
}
