<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Scheme\Interfaces\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;

/**
 * Class HttpsImmutableQuery
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpsImmutableQuery extends AbstractImmutable implements ImmutableBagInterface
{
    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];
}