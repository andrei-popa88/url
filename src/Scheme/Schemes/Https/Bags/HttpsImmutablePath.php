<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Https\Bags;

use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Traits\Accessor;

/**
 * Class HttpsImmutablePath
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpsImmutablePath extends AbstractImmutable implements ImmutableBagInterface
{
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

    /**
     * This should be the ONLY entry point and it should accept ONLY the raw string
     *
     * HttpsImmutablePath constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        // Leave the class with defaults if no valid raw string is provided
        if ('' !== trim($raw)) {
            $this->raw = $raw;

            // explode by /
            $this->path = explode('/', trim($raw, '/'));
        }
    }

//////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return string
     */
    public function first(): ?string
    {
        return $this->firstInPath($this->path);
    }

    /**
     * @return string
     */
    public function last(): ?string
    {
        return $this->lastInPath($this->path);
    }

    /**
     * @param int $key
     * @return mixed
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get(int $key)
    {
        return $this->getKeyIn($this->path, $key);
    }

    /**
     * @param int $key
     * @return bool
     */
    public function has(int $key): bool
    {
        return $this->hasKeyIn($this->path, $key);
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION ///
///////////////////////////////

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
        return $this->raw;
    }
}
