<?php
declare(strict_types=1);

namespace Keppler\Url;

use Keppler\Url\Builder\UrlBuilder;
use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Exceptions\SchemaNotSupportedException;
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
     * @param string $url
     *
     * @return Url
     * @throws MalformedUrlException
     * @throws SchemaNotSupportedException
     */
    public static function from(string $url): self
    {
        $self = new self();
        $self->init($url);
        return $self;
    }

    /**
     * @param string $url
     *
     * @throws MalformedUrlException
     * @throws SchemaNotSupportedException
     */
    private function init(string $url): void
    {
        $schemaFromUrl = parse_url($url);

        if(!isset($schemaFromUrl['scheme'])) {
            throw new MalformedUrlException("Missing scheme");
        }

        $schemaFromUrl = $schemaFromUrl['scheme'];
        if(!in_array($schemaFromUrl, $this->allowedSchemas)) {
            throw new SchemaNotSupportedException(vsprintf("Scheme not allowed. Only %s, %s, and %s are supported. If you need additional schemas extend this class and roll your own implementation.", $this->allowedSchemas));
        }

        $this->parser = new UrlParser($url);
        $this->builder = new UrlBuilder(clone($this->parser));
    }
}
