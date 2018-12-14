<?php
declare(strict_types=1);

namespace Keppler\Url\Interfaces\Immutable;

/**
 * Interface ImmutableSchemeInterface
 *
 * @package Keppler\Url\Scheme\Interfaces
 */
interface ImmutableSchemeInterface
{

    /**
     * Returns all the components of the scheme including
     *  any bags in the form of an array
     *
     * @return array
     */
    public function all(): array;

    /**
     * Return the raw unaltered url
     *
     * @return string
     */
    public function raw(): string;

    /**
     * Returns the scheme associated with the class
     *
     * @return string
     */
    public function getScheme();
}