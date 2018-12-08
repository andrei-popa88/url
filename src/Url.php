<?php
declare(strict_types=1);

namespace Keppler\Url;

use Keppler\Url\Builder\UrlBuilder;
use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Parser\UrlParser;

/**
 * Class Url
 *
 * @package Url
 */
class Url
{
    /**
     * @var array
     */
    protected $allowedSchemas = [
      'http',
      'https',
      'mailto',
    ];

    /**
     * @var UrlParser
     */
    public $parser;

    /**
     * @var UrlBuilder
     */
    public $builder;

    /**
     * Url constructor.
     *
     * @param string $url
     *
     * @throws \Exception
     */
    public function __construct(string $url)
    {
        $schemaFromUrl = parse_url($url);

        if(!isset($schemaFromUrl['scheme'])) {
            throw new MalformedUrlException("Missing scheme");
        }

        $schemaFromUrl = $schemaFromUrl['scheme'];
        if(!in_array($schemaFromUrl, $this->allowedSchemas)) {
            throw new \Exception(vsprintf("Scheme not allowed. Only %s, %s, and %s are supported. If you need additional schemas extend this class and roll your own implementation.", $this->allowedSchemas));
        }

        $this->parser = new UrlParser($url);
        $this->builder = new UrlBuilder(clone($this->parser));
    }
}
