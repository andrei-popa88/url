<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Https\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;

/**
 * Class HttpsMutableQuery
 * @package Keppler\Url\Builder\Schemes\Https\Bags
 */
class HttpsMutableQuery implements MutableBagInterface
{
    use Accessor;

    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];

    /**
     * @var string
     */
    private $raw = '';

    /**
     * This should be the ONLY entry point and it should accept ONLY the raw string
     *
     * HttpsImmutableQuery constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        // Leave the class with defaults if no valid raw string is provided
        if ('' !== trim($raw)) {
            $this->raw = $raw;

            $result = [];
            parse_str($raw, $result);
            $this->buildFromParsed($result);
        }
    }

///////////////////////////
/// PRIVATE FUNCTIONS  ///
/////////////////////////

    /**
     * @param $result
     */
    private function buildFromParsed($result)
    {
        $this->query = $result;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
////////////////////////////////

    /**
     * @param $key
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get($key)
    {
        $this->getIn($this->query, $key);
    }

    /**
     * @param $key
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
        return $this->raw;
    }

    /**
     * Returns the encoded query or path string
     *
     * @return string
     */
    public function encoded(): string
    {
        // TODO: Implement encoded() method.
    }

    /**
     * Sets a given key => value to the query or path
     * Some bags should set a class property if they
     * contain multidimensional values by default
     *
     * @param $key
     * @param $value
     * @throws ComponentNotFoundException
     * @return MutableBagInterface
     */
    public function set($key, $value): MutableBagInterface
    {
        // TODO: Implement set() method.
    }

}