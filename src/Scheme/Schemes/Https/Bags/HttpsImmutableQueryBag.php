<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Scheme\Interfaces\BagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;

/**
 * Class HttpsImmutableQueryBag
 * @package Keppler\Url\Schemes\Http\Bags
 */
final class HttpsImmutableQueryBag extends AbstractImmutable implements BagInterface
{
    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];
}