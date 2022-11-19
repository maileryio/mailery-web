<?php

namespace Mailery\Web\Vue;

use Yiisoft\Html\NoEncodeStringableInterface;
use Yiisoft\Html\Tag\Base\NormalTag;
use \Yiisoft\Html\Tag\Base\TagContentTrait;

class Directive extends NormalTag implements NoEncodeStringableInterface
{

    use TagContentTrait;

    /**
     * @param string|\Stringable $string
     * @return self
     */
    public static function pre(string|\Stringable $string): self
    {
        return self::tag()
            ->content($string)
            ->attribute('v-pre', true);
    }

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'span';
    }

}
