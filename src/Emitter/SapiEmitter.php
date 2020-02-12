<?php

declare(strict_types=1);

/**
 * Mailery package for provide web components
 * @link      https://github.com/maileryio/mailery-web
 * @package   Mailery\Web
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Web\Emitter;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\Web\Emitter\EmitterInterface;

/**
 * SapiEmitter sends a response using PHP Server API
 */
final class SapiEmitter implements EmitterInterface
{
    private const NO_BODY_RESPONSE_CODES = [204, 205, 304];

    /**
     * @param ResponseInterface $response
     * @param bool $withoutBody
     * @return bool
     */
    public function emit(ResponseInterface $response, bool $withoutBody = false): bool
    {
        $status = $response->getStatusCode();

        // @todo временный фикс, виджет не должен делать echo, потом заменить на Yiisoft\Yii\Web\Emitter\SapiEmitter
        if (headers_sent()) {
            return true;
        }

        header_remove();
        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                header(sprintf(
                    '%s: %s',
                    $header,
                    $value
                ), $header !== 'Set-Cookie', $status);
            }
        }

        $reason = $response->getReasonPhrase();

        header(sprintf(
            'HTTP/%s %d%s',
            $response->getProtocolVersion(),
            $status,
            ($reason !== '' ? ' ' . $reason : '')
        ), true, $status);

        if ($withoutBody === false && $this->shouldOutputBody($response)) {
            $contentLength = $response->getBody()->getSize();
            if ($response->hasHeader('Content-Length')) {
                $contentLengthHeader = $response->getHeader('Content-Length');
                $contentLength = array_shift($contentLengthHeader);
            }

            header(sprintf('Content-Length: %d', (int) $contentLength), true, $status);

            echo (string) $response->getBody();
        }

        return true;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    private function shouldOutputBody(ResponseInterface $response): bool
    {
        return !\in_array($response->getStatusCode(), self::NO_BODY_RESPONSE_CODES, true);
    }
}
