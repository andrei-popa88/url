<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Ftp\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class HttpImmutablePath
 * @package Keppler\Url\Schemes\Http\Bags
 */
class FtpMutablePath implements MutableBagInterface
{
    use Accessor;
    use Mutator;

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
     * @return string|null
     */
    public function first(): ?string
    {
        return $this->firstInPath($this->path);
    }

    /**
     * @return null|string
     */
    public function last()
    {
        return $this->lastInPath($this->path);
    }

    /**
     * @param string $value
     *
     * @return FtpMutablePath
     */
    public function append(string $value): self
    {
        $this->mutatorAppend($this->path, $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return FtpMutablePath
     */
    public function prepend(string $value): self
    {
        $this->mutatorPrepend($this->path, $value);

        return $this;
    }

    /**
     * @param string      $value
     * @param string|null $first
     * @param string|null $last
     *
     * @return FtpMutablePath
     * @throws ComponentNotFoundException
     */
    public function putInBetween(string $value, string $first = null, string $last = null): self
    {
        if(null === $first && null === $last) {
            throw new \LogicException('Cannot put value if neither first or last is defined');
        }

        if(!$this->hasValueIn($this->path, $first) && !$this->hasValueIn($this->path, $last)) {
            throw new ComponentNotFoundException(sprintf('No component found matching either %s  %s',$first, $last));
        }

        $this->mutatorPutInBetweenKeys($this->path, $value, $first, $last);

        return $this;
    }

    /**
     * @param string $before
     * @param string $value
     *
     * @return FtpMutablePath
     * @throws \LogicException
     */
    public function putBefore(string $before, string $value) : self
    {
        if(!$this->hasValueIn($this->path, $before)) {
            throw new \LogicException(sprintf('Cannot put value %s before %s as %s does not exist', $value, $before, $before));
        }

        $this->path = $this->mutatorPutBeforeValue($this->path, $before, $value);

        return $this;
    }

    /**
     * @param string $after
     * @param string $value
     *
     * @return FtpMutablePath
     * @throws \LogicException
     */
    public function putAfter(string $after, string $value): self
    {
        if(!$this->hasValueIn($this->path, $after)) {
            throw new \LogicException(sprintf('Cannot put value %s after %s as %s does not exist', $value, $after, $after));
        }

        $this->path = $this->mutatorPutAfterValue($this->path, $after, $value);

        return $this;
    }

    /**
     * @param string ...$args
     *
     * @return FtpMutablePath
     */
    public function forget(string ...$args): self
    {
        foreach($args as $item) {
            if(!$this->hasValueIn($this->path, $item)) {
                throw new \LogicException(sprintf('Cannot forget %s as it does not exist', $item));
            }

            $this->mutatorForgetKeyOrValue($this->path, $item);
        }

        return $this;
    }

    /**
     * @return FtpMutablePath
     */
    public function forgetAll(): self
    {
        $this->path = [];

        return $this;
    }

    /**
     * @param string ...$args
     * @return array
     */
    public function only(string ...$args): array
    {
        return $this->mutatorOnlyPathValues($this->path, $args);
    }

    /////////////////////////////////
    /// INTERFACE IMPLEMENTATION ///
    ///////////////////////////////

    /**
     * @param $key
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function get($key)
    {
        return $this->getKeyIn($this->path, $key);
    }

    /**
     * @param $key
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
     * @return FtpMutablePath
     */
    public function set($key, $value): self
    {
        $this->path[$key] = $value;

        return $this;
    }
}
