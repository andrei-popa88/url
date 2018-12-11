<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Interfaces;

/**
 * Interface ImmutableQueryBagInterface
 * @package Keppler\Url\Schemes\Interfaces
 */
interface ImmutableQueryBagInterface
{
    /**
     * Should return all the query parts in the form of an array
     *
     * @return array
     */
    public function all(): array;
}