<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes;

use Keppler\Url\Exceptions\ImmutableException;

/**
 * Not truly immutable since reflection is always an option
 *
 * Class AbstractImmutable
 *
 * @package Keppler\Url\Scheme\Schemes
 */
class AbstractImmutable
{
    /**
     * Don't __set my stuff you heathen!
     *
     * This is here mostly because somebody may make a mistake and do something like $immutable->unexistingProperty = something
     * which would actually work without throwing an error if the bellow wouldn't throw one
     *
     * @param $key
     * @param $value
     *
     * @throws ImmutableException
     */
    public function __set($key, $value)
    {
        throw new ImmutableException(sprintf('Immutable class %s cannot be changed', __CLASS__));
    }
}