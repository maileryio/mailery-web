<?php

namespace Mailery\Web\Widget;

use Yiisoft\Widget\Widget;
use \DateTime;
use \DateTimeImmutable;

final class DateTimeFormat extends Widget
{

    /**
     * @var DateTime|DateTimeImmutable
     */
    private DateTime|DateTimeImmutable $dateTime;

    /**
     * @param DateTime|DateTimeImmutable $dateTime
     * @return self
     */
    public function dateTime(DateTime|DateTimeImmutable $dateTime): self
    {
        $new = clone $this;
        $new->dateTime = $dateTime;

        return $new;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }
}