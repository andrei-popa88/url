<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Http\Bags;

/**
 * Class HttpImmutableQueryBag
 * @package Keppler\Url\Schemes\Http\Bags
 */
final class HttpImmutableQueryBag
{
    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];
}