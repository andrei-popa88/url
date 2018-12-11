<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Traits;

/**
 * Trait Filter
 * @package Keppler\Url\Scheme\Traits
 */
trait Filter
{
    /**
     * Returns the first key => value pair of an array
     *
     * @param array $array
     * @return array
     */
    protected function firstIn(array $array): array
    {
        return [key($array) => reset($array)];
    }

    /**
     * @param array $array
     * @return array
     */
    protected function lastIn(array $array): array
    {
        $array_revers = array_reverse($array);
        return [key($array_revers) => reset($array_revers)];
    }
}