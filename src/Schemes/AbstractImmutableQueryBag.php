<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes;

/**
 * Class AbstractImmutableQueryBag
 *
 * @package Keppler\Url\Schemes
 */
abstract class AbstractImmutableQueryBag
{
    /**
     * Should return all the query parts in the form of an array
     *
     * @return array
     */
    abstract function all();

    /**
     * Should return the first entry in the query
     *
     * @return string
     */
    abstract function first();

    /**
     * Should return the last entry in the query
     *
     * @return string
     */
    abstract function last();

    /**
     * Should return the unaltered query string as is
     *
     * @return string
     */
    abstract function raw();

    /**
     * Should return weather a given index exists
     *
     * @param $index
     *
     * @return bool
     */
    abstract function has($index);

    /**
     * Should get a given index
     *
     * @param $index
     *
     * @return mixed
     */
    abstract function get($index);
}