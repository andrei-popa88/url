<?php
declare(strict_types=1);

namespace Keppler\Url\Traits;

/**
 * Trait Mutator
 * @package Keppler\Url\Traits
 */
trait Mutator
{
    /**
     * @param $to
     * @param $value
     */
    protected function append(array $to, $value)
    {
        array_push($to, $value);
    }

    /**
     * @param $to
     * @param $value
     */
    protected function prepend(array $to, $value)
    {
        array_unshift($to, $value);
    }

    /**
     * @param $from
     * @param $keyOrValue
     */
    protected function forgetKeyOrValue(array &$from, $keyOrValue)
    {
        if(isset($from[$keyOrValue])) {
            unset($from[$keyOrValue]);
            return;
        }

        foreach($from as $key => $value) {
            if($value === $keyOrValue) {
                unset($from[$key]);
                break;
            }
        }
    }

    /**
     * @param $what
     * @return bool
     */
    protected function isEmpty($what)
    {
        return empty($what);
    }
}