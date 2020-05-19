<?php

namespace Mailery\Web\Widget;

use Yiisoft\Widget\Widget;
use Mailery\Common\Entity\RoutableEntityInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Html\Html;

class EntityViewLink extends Widget
{
    /**
     * @var object
     */
    private object $entity;

    /**
     * @var string
     */
    private string $label;

    /**
     * @var array
     */
    private array $params = [];

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param object $entity
     * @return self
     */
    public function entity(object $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param string $label
     * @return self
     */
    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param array $params
     * @return self
     */
    public function params(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param array $options
     * @return self
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function run(): string
    {
        $entity = $this->entity;

        if ($entity instanceof RoutableEntityInterface && ($routeName = $entity->getViewRouteName()) !== null) {
            $routeParams = array_merge($entity->getViewRouteParams(), $this->params);

            return Html::a(
                $this->label,
                $this->urlGenerator->generate($routeName, $routeParams)
            );
        }

        return $this->label;
    }
}
