<?php

namespace Mailery\Web\Widget;

use Yiisoft\Widget\Widget;
use Mailery\Common\Entity\RoutableEntityInterface;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Html\Html;
use Cycle\ORM\ORMInterface;

class EntityViewLink extends Widget
{
    /**
     * @var object
     */
    private object $entity;

    /**
     * @var string|\Stringable
     */
    private string|\Stringable $label;

    /**
     * @var bool
     */
    private bool $reload = false;

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var array
     */
    private array $routeParams = [];

    /**
     * @param ORMInterface $orm
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        private ORMInterface $orm,
        private UrlGeneratorInterface $urlGenerator
    ) {}

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
     * @param string|\Stringable $label
     * @return self
     */
    public function label(string|\Stringable $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param bool $reload
     * @return self
     */
    public function reload(bool $reload): self
    {
        $this->reload = $reload;

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
     * @param array $routeParams
     * @return self
     */
    public function routeParams(array $routeParams): self
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function run(): string
    {
        $entity = $this->getEntity();
        if ($entity instanceof RoutableEntityInterface && ($routeName = $entity->getViewRouteName()) !== null) {
            $routeParams = array_merge($entity->getViewRouteParams(), $this->routeParams);

            return Html::a(
                $this->label,
                $this->urlGenerator->generate($routeName, $routeParams)
            );
        }

        return $this->label;
    }

    /**
     * @return object|null
     */
    private function getEntity(): ?object
    {
        if ($this->reload && method_exists($this->entity, 'getId') && !empty($this->entity->getId())) {
            $repo = $this->orm->getRepository(get_class($this->entity));
            return $repo->findByPK($this->entity->getId());
        }
        return $this->entity;
    }
}
