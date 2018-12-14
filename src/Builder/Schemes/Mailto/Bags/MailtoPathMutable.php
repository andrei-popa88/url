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
     * @var string
     */
    private $raw = '';


    /**
     * @param $path array|string
     * @return $this
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
    public function appendToPath(string $value): self
    {
        $this->append($this->path, $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return MailtoPathMutable
     */
    public function prependToPath(string $value): self
    {
        $this->prepend($this->path, $value);

        return $this;
    }

    /**
     * @return array|string
     */
    public function firstInPath()
    {
        return $this->firstIn($this->path);
    }

    /**
     * @return array|string
     */
    public function lastInPath()
    {
        return $this->lastIn($this->path);
    }

    /**
     * @param $keyOrValue
     *
     * @return MailtoPathMutable
     */
    public function forgetInPath($keyOrValue): self
    {
        $this->mutatorForgetKeyOrValue($this->path, $keyOrValue);

        return $this;
    }

    /**
     * @return MailtoPathMutable
     */
    public function forgetPath(): self
    {
        $this->path = '';

        return $this;
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
        return $this->raw;
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
     * Checks weather a given bag or path has a certain key
     *
     * @param string $value
     *
     * @return bool
     */
    public function has($value): bool
    {
        return $this->hasValueIn($this->path, $value);
    }

    /**
     * Gets a given key from the query or path
     * Some bags CAN and SHOULD return a class property
     * If the given bag has predefined set of values
     * for example MailtoImmutableQuery
     *
     * @param $key
     *
     * @throws ComponentNotFoundException
     * @return mixed
     */
    public function get($value)
    {
        // TODO: Implement get() method.
    }

    /**
     * Sets a given key => value to the query or path
     * Some bags should set a class property if they
     * contain multidimensional values by default
     *
     * @param $key
     * @param $value
     *
     * @throws ComponentNotFoundException
     * @return MutableBagInterface
     */
    public function set($key, $value): self
    {
        // TODO: Implement set() method.
    }


}
