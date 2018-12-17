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
     * @param array $path
     * @return false|mixed|null
     */
    protected function firstInPath(array $path)
    {
        return false !== reset($path) ? reset($path) : null;
    }

    /**
     * @param array $query
     * @return array|null
     */
    protected function firstInQuery(array $query)
    {
        return false !== reset($query) ? [key($query) => reset($query)] : null;
    }

    /**
     * Returns the last key => value pair of an array
     *
     * @param array $array
     * @return string
     */
    protected function lastInPath(array $array): ?string
    {
        $array_revers = array_reverse($array);

        return false !== reset($array_revers) ? (string)reset($array_revers) : null;
    }

    /**
     * @param array $query
     * @return array|null
     */
    protected function lastInQuery(array $query)
    {
        $array_revers = array_reverse($query);

        return false !== reset($array_revers) ? [key($array_revers) => reset($array_revers)] : null;
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
    protected function getKeyIn($in, $key)
    {
        if (!array_key_exists($key, $in)) {
            throw new ComponentNotFoundException(sprintf('Component with index "%s" does not exist in %s', $key,
                __CLASS__));
        }

        return $in[$key];
    }

    /**
     * @param array $in
     * @param       $value
     *
     * @return mixed
     * @throws ComponentNotFoundException
     */
    protected function getValueIn(array $in, $value) {
        foreach($in as $element) {
            if($element === $value) {
                return $value;
            }
        }

        throw new ComponentNotFoundException(sprintf('Component with index "%s" does not exist in %s', $value,
            __CLASS__));
    }

    /**
     * @param array $in
     * @param $value
     * @return bool
     */
    protected function hasValueIn(array $in, $value): bool
    {
        return array_key_exists($value, array_flip($in));
    }

    /**
     * @param $in
     * @param $key
     * @return bool
     */
    protected function hasKeyIn(array $in, $key): bool
    {
        return array_key_exists($key, $in);
    }

    /**
     * Recursively walks the array without breaking the iteration with return
     *
     * @param $array
     *
     * @return \Generator
     */
    protected function walkRecursive(array $array): \Generator
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $key => $value) {
            yield $key => $value;
        }
    }
}