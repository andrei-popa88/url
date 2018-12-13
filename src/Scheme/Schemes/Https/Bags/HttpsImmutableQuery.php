<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Https\Bags;

use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;
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

    /**
     * @var string
     */
    private $raw = '';

    /**
     * This should be the ONLY entry point and it should accept ONLY the raw string
     *
     * MailtoImmutableQuery constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        // Leave the class with defaults if no valid raw string is provided
        if ('' !== trim($raw)) {
            $this->raw = $raw;

            $result = [];
            parse_str($raw, $result);
//            $this->buildFromParsed($result);
        }
    }

    /**
     * Returns all the components of the query or path
     *
     * @return array
     */
    public function all(): array
    {
        // TODO: Implement all() method.
    }

    /**
     * Return the raw unaltered query or path
     *
     * @return string
     */
    public function raw(): string
    {
        // TODO: Implement raw() method.
    }
}