<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web\View;

use Yiisoft\View\WebView as YiiWebView;

class WebView extends YiiWebView
{
    /**
     * @var string
     */
    private string $language;

    /**
     * @var string
     */
    private string $encoding;

    /**
     * @var array
     */
    private array $breadcrumbs = [];

    /**
     * @return void
     */
    public function registerCsrfMetaTags(): void
    {
    }

    /**
     * @param string $language
     * @return void
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding(string $encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    /**
     * @param array|string $breadcrumb
     */
    public function addBreadcrumb($breadcrumb)
    {
        if (is_string($breadcrumb)) {
            $this->breadcrumbs['label'] = $breadcrumb;
        } else {
            $this->breadcrumbs[] = $breadcrumb;
        }
    }

    /**
     * @return string
     */
    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }
}
