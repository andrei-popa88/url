<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\Parser\UrlParser;

/**
 * Class UrlBuilder
 *
 * @package Url\Builder
 */
class UrlBuilder
{
    /**
     * @var UrlParser
     */
    private $url;

    /**
     * UrlBuilder constructor.
     *
     * @param UrlParser $url
     */
    public function __construct(UrlParser $url)
    {
        $this->url = $url;
    }
}