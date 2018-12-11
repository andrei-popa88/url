<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Interfaces;

/**
 * Interface BagInterface
 *
 * @package Keppler\Url\Scheme\Interfaces
 */
interface BagInterface
{

    /**
     * Returns all the components of the query or path
     *
     * @return array
     */
    public function all(): array;

    /**
     * Return the raw unaltered query or path
     *
     * @return string
     */
    public function raw(): string;
}