<?php
declare(strict_types=1);

namespace Keppler\Url\Interfaces\Mutable;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;

/**
 * Interface MutableBagInterface
 * @package Keppler\Url\Builder\Schemes\Interfaces
 */
interface MutableBagInterface extends ImmutableBagInterface
{
    /**
     * Returns the encoded query or path string
     *
     * @return string
     */
    public function encoded(): string;

    /**
     * Checks weather a given bag or path has a certain key
     *
     * @param string $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Gets a given key from the query or path
     * Some bags CAN and SHOULD return a class property
     * If the given bag has predefined set of values
     * for example MailtoImmutableQuery
     *
     * @param $key
     * @throws ComponentNotFoundException
     * @return mixed
     */
    public function get($key);

    /**
     * Sets a given key => value to the query or path
     * Some bags should set a class property if they
     * contain multidimensional values by default
     *
     * @param $key
     * @param $value
     * @throws ComponentNotFoundException
     * @return MutableBagInterface
     */
    public function set($key, $value);
}