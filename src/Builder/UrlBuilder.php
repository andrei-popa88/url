<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\Parser\Parser;

/**
 * Class UrlBuilder
 *
 * @package Url\Builder
 */
class UrlBuilder
{
    /**
     * @var Parser
     */
    private $url;

    /**
     * UrlBuilder constructor.
     *
     * @param Parser $url
     */
    public function __construct(Parser $url)
    {
        $this->url = $url;
    }
}