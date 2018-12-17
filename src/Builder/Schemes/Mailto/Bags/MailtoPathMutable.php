<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

class MailtoPathMutable implements MutableBagInterface
{
    use Accessor;
    use Mutator;

    /**
     * The path can be either a string or a comma separated value of strings
     *
     * @example mailto:john@gmail.com,test@gmail.com is an array
     * @example mailto:john@gmail.com is a string
     *
     * @var string | array
     */
    private $path = [];

    /**
     * @param $path array|string
     * @return MailtoPathMutable
     * @throws InvalidComponentsException
     */
    public function setPath(array $path): self
    {
        if (is_array($path)) {
            if (count($path) !== count($path, COUNT_RECURSIVE)) {
                throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for $path component in %s',
                    __CLASS__));
            }
        }

        $this->path = $path;

        return $this;
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }

    /**
     * @param string $value
     *
     * @return MailtoPathMutable
     */
    public function append(string $value): self
    {
        $this->mutatorAppend($this->path, $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return MailtoPathMutable
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
     * @return MailtoPathMutable
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
     * @return MailtoPathMutable
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
     * @return MailtoPathMutable
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
     * @return MailtoPathMutable
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
     * @return MailtoPathMutable
     */
    public function forgetAll(): self
    {
        $this->path = [];

        return $this;
    }

    /**
     * @param string ...$args
     *
     * @return array
     */
    public function only(string ...$args): array
    {
        foreach($args as $item) {
            if(!$this->hasValueIn($this->path, $item)) {
                throw new \LogicException(sprintf('Value %s does not exist', $item));
            }
        }

        return $this->mutatorOnlyValues($this->path, $args);
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

        $path = '';
        foreach($this->path as $element) {
            $path .= ($element) . ',';
        }
        return rtrim($path, ',');
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

        $path = '';
        foreach($this->path as $element) {
            $path .= ($element) . urlencode(',');
        }
        return rtrim($path, urlencode(','));
    }

    /**
     * Checks weather a given bag or path has a certain key
     *
     * @param int $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return $this->hasKeyIn($this->path, $key);
    }

    /**
     * Gets a given value from the path
     *
     * @param $key
     *
     * @throws ComponentNotFoundException
     * @return mixed
     */
    public function get($key)
    {
        if(!$this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s', $key, __CLASS__));
        }

        return $this->getKeyIn($this->path, $key);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return MailtoPathMutable
     */
    public function set($key, $value): self
    {
        if(!is_int($key)) {
            throw new \LogicException(sprintf('Method %s can only accept integers as a key', __METHOD__));
        }

        $this->path[$key] = $value;

        return $this;
    }


}
