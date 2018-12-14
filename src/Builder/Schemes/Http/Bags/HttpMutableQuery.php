<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Http\Bags;

use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class HttpMutableQuery
 *
 * @package Keppler\Url\Builder\Schemes\Https\Bags
 */
class HttpMutableQuery implements MutableBagInterface
{
    use Mutator;
    use Accessor;

    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];


    /**
     * @return string
     */
    public function first(): string
    {
        return $this->firstIn($this->query);
    }

    /**
     * @return string
     */
    public function last(): string
    {
        return $this->lastIn($this->query);
    }

    /**
     * @param string $value
     *
     * @return HttpMutableQuery
     */
    public function put(string $value): self
    {
        $this->mutatorAppend($this->query, $value);

        return $this;
    }

    /**
     * @param string ...$args
     *
     * @return HttpMutableQuery
     */
    public function forget(string ...$args): self
    {
        foreach ($args as $item) {
            if ( ! $this->hasValueIn($this->query, $item)) {
                throw new \LogicException(sprintf('Cannot forget %s as it does not exist',
                    $item));
            }

            $this->mutatorForgetKeyOrValue($this->query, $item);
        }

        return $this;
    }

    /**
     * @return HttpMutableQuery
     */
    public function forgetAll(): self
    {
        $this->query = [];

        return $this;
    }

    /**
     * @param string ...$args
     *
     * @return HttpMutableQuery
     */
    public function only(string ...$args): self
    {
        foreach ($args as $item) {
            if ( ! $this->hasValueIn($this->query, $item)) {
                throw new \LogicException(sprintf('Cannot forget %s as it does not exist',
                    $item));
            }
        }

        $this->query = $this->mutatorOnlyValues($this->query, $args);

        return $this;
    }

    /////////////////////////////////
    /// INTERFACE IMPLEMENTATION  ///
    ////////////////////////////////

    /**
     * @param $key
     *
     * @return mixed|void
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get($key)
    {
        $this->getKeyIn($this->query, $key);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return $this->hasKeyIn($this->query, $key);
    }

    /**
     * Returns all the components of the query
     *
     * @return array
     */
    public function all(): array
    {
        return $this->query;
    }

    /**
     * Return the raw unaltered query
     *
     * @return string
     */
    public function raw(): string
    {
        if ( ! empty($this->query)) {
            return '?'.urldecode(http_build_query($this->query));
        }

        return '';
    }

    /**
     * Returns the encoded query or path string
     *
     * @return string
     */
    public function encoded(): string
    {
        if ( ! empty($this->query)) {
            return '?'.http_build_query($this->query);
        }

        return '';
    }

    /**
     * Sets a given key => value to the query or path
     * Some bags should set a class property if they
     * contain multidimensional values by default
     *
     * @param $key
     * @param $value
     *
     * @return self
     */
    public function set($key, $value): self
    {
        $this->query[$key] = $value;

        return $this;
    }
}
