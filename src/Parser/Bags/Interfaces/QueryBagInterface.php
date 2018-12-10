<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags\Interfaces;

/**
 * Interface QueryBagInterface
 * @package Keppler\Url\Parser\Bags\Interfaces
 */
interface QueryBagInterface
{
    public function raw();
    public function firstIn($index);
    public function lastIn($index);
    public function first(): array;
    public function last(): array;
    public function has(string $component);
    public function get(string $component);
    public function all(): array;
    public function walkRecursive();
}