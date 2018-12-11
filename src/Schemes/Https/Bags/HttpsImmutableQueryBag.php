<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Http\Bags;

/**
 * Class HttpsImmutableQueryBag
 * @package Keppler\Url\Schemes\Http\Bags
 */
final class HttpsImmutableQueryBag
{
    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];
}