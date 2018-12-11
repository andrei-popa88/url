<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Interfaces;

/**
 * Interface ImmutablePathBagInterface
 * @package Keppler\Url\Schemes\Interfaces
 */
interface ImmutablePathBagInterface
{
    /**
     * Should return all the path parts in the form of an array
     *
     * @return array
     */
    public function all(): array;

    /**
     * Should return the first entry in the path
     *
     * @return string
     */
    public function first(): string;

    /**
     * Should return the last entry in the path
     *
     * @return string
     */
    public function last(): string;

    /**
     * Should return the unaltered path string as is
     *
     * @return string
     */
    public function raw(): string;

    /**
     * Should return weather a given index exists
     *
     * @param $index
     *
     * @return bool
     */
    public function has(string $index): bool;

    /**
     * Should get a given index
     *
     * @param $index
     *
     * @return mixed
     */
    public function get(string $index): string;
}