<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Scheme\Interfaces\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;

/**
 * Class HttpImmutableQuery
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpImmutableQuery extends AbstractImmutable implements ImmutableBagInterface
{
    /**
     * query = *( pchar / "/" / "?" )
     *
     * @var array
     */
    private $query = [];
}