<?php

namespace Mailery\Web\Widget;

use Mailery\Common\Field\Timezone;
use Mailery\User\Service\CurrentUserService;
use Yiisoft\Widget\Widget;
use \DateTimeImmutable;

final class DateTimeFormat extends Widget
{

    /**
     * @var string
     */
    private string $format = 'Y-m-d H:i:s';

    /**
     * @var Timezone
     */
    private Timezone $timezone;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $dateTime;

    /**
     * @param CurrentUserService $currentUser
     */
    public function __construct(CurrentUserService $currentUser)
    {
        $this->timezone = $currentUser->getUser()->getTimezone();
    }

    /**
     * @param string $format
     * @return self
     */
    public function format(string $format): self
    {
        $new = clone $this;
        $new->format = $format;

        return $new;
    }

    /**
     * @param Timezone $timezone
     * @return self
     */
    public function timezone(Timezone $timezone): self
    {
        $new = clone $this;
        $new->timezone = $timezone;

        return $new;
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return self
     */
    public function dateTime(DateTimeImmutable $dateTime): self
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
        return $this->dateTime
            ->setTimeZone(new \DateTimeZone($this->timezone->getValue()))
            ->format($this->format);
    }
}