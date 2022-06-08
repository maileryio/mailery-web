<?php

namespace Mailery\Web\Widget;

use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

class BooleanBadge extends Widget
{
    /**
     * @var string|null
     */
    private ?string $tag = 'span';

    /**
     * @var array
     */
    private array $options = [];

    /**
     * @var bool
     */
    private bool $value = false;

    /**
     * @param string $tag
     * @return self
     */
    public function tag(string $tag): self
    {
        $this->tag = $tag;

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
     * @param bool $value
     * @return self
     */
    public function value(bool $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function run(): string
    {
        $options = $this->options;

        if ($this->value === true) {
            $cssClass = 'badge-success';
            $label = 'Enable';
        } else {
            $cssClass = 'badge-danger';
            $label = 'Disabled';
        }

        Html::addCssClass($options, 'badge ' . $cssClass);

        return Html::tag(
            $this->tag,
            $label,
            $options
        )->render();
    }
}
