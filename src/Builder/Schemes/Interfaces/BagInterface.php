<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Interfaces;

/**
 * Interface BagInterface
 * @package Keppler\Url\Builder\Schemes\Interfaces
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

    /**
     * Checks weather a given bag or path has a certain key
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Gets a given key from the query or path
     * Some bags CAN and SHOULD return a class property
     * If the given bag has predefined set of values
     * for example MailtoImmutableQuery
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Sets a given key => value to the query or path
     * Some bags should set a class property if they
     * contain multidimensional values by default
     *
     * @param $key
     * @param $value
     * @return BagInterface
     */
    public function set($key, $value): self;
}