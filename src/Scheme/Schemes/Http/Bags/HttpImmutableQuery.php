<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Traits\Accessor;

/**
 * Class HttpImmutableQuery
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpImmutableQuery extends AbstractImmutable implements ImmutableBagInterface
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
     * HttpImmutableQuery constructor.
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

//////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @param $key
     * @throws \Keppler\Url\Exceptions\ComponentNotFoundException
     */
    public function get($key)
    {
        $this->getKeyIn($this->query, $key);
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
     * @return null|string
     */
    public function first()
    {
        return $this->firstIn($this->query);
    }

    public function last()
    {
        return $this->lastIn($this->query);
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
////////////////////////////////

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
}