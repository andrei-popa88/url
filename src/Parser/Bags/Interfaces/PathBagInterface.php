<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags\Interfaces;

/**
 * Interface PathBagInterface
 * @package Keppler\Url\Parser\Bags\Interfaces
 */
interface PathBagInterface
{
    public function all();
    public function raw();
    public function get(int $index): ?string;

    public function has(int $index): bool;

    public function first(): ?string;

    public function last(): ?string;
}
