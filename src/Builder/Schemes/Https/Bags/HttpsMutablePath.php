<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Https\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class HttpsMutablePath
 * @package Keppler\Url\Builder\Schemes\Https\Bags
 */
class HttpsMutablePath implements  MutableBagInterface
{
    use Mutator;
    use Accessor;

    /**
     *  path = path-abempty    ; begins with "/" or is empty
     *
     *
     *  / path-absolute   ; begins with "/" but not "//"
     *  / path-noscheme   ; begins with a non-colon segment
     *  / path-rootless   ; begins with a segment
     *  / path-empty      ; zero characters
     *
     *  path-abempty  = *( "/" segment )
     *  path-absolute = "/" [ segment-nz *( "/" segment ) ]
     *  path-noscheme = segment-nz-nc *( "/" segment )
     *  path-rootless = segment-nz *( "/" segment )
     *  path-empty    = 0<pchar>
     *  segment       = *pchar
     *  segment-nz    = 1*pchar
     *  segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
     *  ; non-zero-length segment without any colon ":"
     *
     *  pchar         = unreserved / pct-encoded / sub-delims / ":" / "@
     *
     * @see https://tools.ietf.org/html/rfc3986#page-22
     *
     * @var array
     */
    private $path = [];

    /**
     * @var string
     */
    private $raw = '';

//////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->firstIn($this->path);
    }

    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->lastIn($this->path);
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION ///
///////////////////////////////

    /**
     * @param $key
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get($key)
    {
        $this->getKeyIn($this->path, $key);
    }

    /**
     * @param int $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->hasKeyIn($this->path, $key);
    }

    /**
     * Returns all the components of the query or path
     *
     * @return array
     */
    public function all(): array
    {
        return $this->path;
    }

    /**
     * Return the raw unaltered query or path
     *
     * @return string
     */
    public function raw(): string
    {
        if(empty($this->path)) {
            return '';
        }

        $path = '/';
        foreach($this->path as $element) {
            $path .= $element . '/';
        }

        return $path;
    }

    /**
     * Returns the encoded query or path string
     *
     * @return string
     */
    public function encoded(): string
    {
        if(empty($this->path)) {
            return '';
        }

        $path = '/';
        foreach($this->path as $element) {
            $path .= urlencode($element) . '/';
        }

        return $path;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return HttpsMutablePath
     */
    public function set($key, $value): self
    {
        $this->path[$key] = $value;

        return $this;
    }
}
