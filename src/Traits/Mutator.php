<?php
declare(strict_types=1);

namespace Keppler\Url\Traits;

/**
 * Trait Mutator
 *
 * @package Keppler\Url\Traits
 */
trait Mutator
{
    /**
     * @param $array
     * @param $value
     */
    protected function mutatorAppend(array &$array, $value)
    {
        array_push($array, $value);
    }

    /**
     * @param $array
     * @param $value
     */
    protected function mutatorPrepend(array &$array, $value)
    {
        array_unshift($array, $value);
    }

    /**
     * @param array $array
     * @param       $before
     * @param       $value
     *
     * @return array
     */
    protected function mutatorPutBeforeValue(array $array, $before, $value)
    {
        $result = [];

        foreach ($array as $key => $item) {
            if ($before === $item) {
                $result[] = $value;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param array $array
     * @param       $after
     * @param       $value
     *
     * @return array
     */
    protected function mutatorPutAfterValue(array $array, $after, $value)
    {
        $result = [];

        foreach ($array as $key => $item) {
            $result[] = $item;

            if ($after === $item) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $array
     * @param       $first
     * @param       $last
     * @param       $value
     */
    protected function mutatorPutInBetweenKeys(
        array &$array,
        $value,
        $first,
        $last
    ) {
        $position = 0;
        if (null !== $first) {
            foreach ($array as $key => $item) {
                $position++;
                if ($first === $item) {
                    break;
                }
            }
        } else {
            foreach ($array as $key => $item) {
                if ($last === $item) {
                    break;
                }
                $position++;
            }
        }

        array_splice($array, $position, 0, $value);
    }

    /**
     * @param array $array
     * @param mixed ...$args
     *
     * @return array
     */
    protected function mutatorOnlyPathValues(array $array, ...$args)
    {
        $result = [];

        foreach($array as $item) {
            if(!in_array($item, $args[0])) {
                continue;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param array $array
     * @param mixed ...$args
     * @return array
     */
    protected function mutatorQueryOnlyValues(array $array, ...$args)
    {
        $result = [];

        foreach($array as $key => $item) {
            if(!in_array($key, $args[0])) {
                continue;
            }

            $result[$key] = $item;
        }

        return $result;
    }

    /**
     * @param $from
     * @param $keyOrValue
     */
    protected function mutatorForgetKeyOrValue(array &$from, $keyOrValue)
    {
        if (isset($from[$keyOrValue])) {
            unset($from[$keyOrValue]);

            return;
        }

        foreach ($from as $key => $value) {
            if ($value === $keyOrValue) {
                unset($from[$key]);
                break;
            }
        }
    }

    /**
     * @param $what
     *
     * @return bool
     */
    protected function isEmpty($what)
    {
        return empty($what);
    }
}