<?php
declare(strict_types=1);

namespace Keppler\Url\Interfaces\Mutable;

use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;

/**
 * Interface MutableSchemeInterface
 * @package Keppler\Url\Interfaces\Mutable
 */
interface MutableSchemeInterface extends ImmutableSchemeInterface
{
    /**
     * @param bool $urlEncode
     * @return string
     */
    public function build(bool $urlEncode = false): string;
}