<?php
declare(strict_types=1);

namespace Keppler\Url\Traits;

use Keppler\Url\Exceptions\ComponentNotFoundException;

/**
 * Trait Accessor
 * @package Keppler\Url\Scheme\Traits
 */
trait Accessor
{
    /**
     * Returns the first key => value pair of an array
     *
     * @param array $array
     * @return array
     */
    protected function firstIn(array $array): array
    {
        return empty($array) ? [] : [key($array) => reset($array)];
    }

    /**
     * This sort of function could have easily been implemented in
     * each class but the single entry point is nice to have
     *
     * @param $in
     * @param $key
     * @return mixed
     * @throws ComponentNotFoundException
     */
    protected function getIn($in, $key)
    {
        if(!array_key_exists($key, $in)) {
            throw new ComponentNotFoundException(sprintf('Component with index "%s" does not exist in %s', $key, __CLASS__));
        }

        return $in[$key];
    }

    /**
     * Returns the last key => value pair of an array
     *
     * @param array $array
     * @return array
     */
    protected function lastIn(array $array): array
    {
        $array_revers = array_reverse($array);
        return empty($array) ? [] : [key($array_revers) => reset($array_revers)];
    }
}