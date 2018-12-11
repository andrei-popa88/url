<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http;

use Keppler\Url\Scheme\Interfaces\SchemeInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the http url is taken AS IS and will not be decoded or encoded
 * url encoded strings WILL result in errors
 *
 *  http://example.com:8042/over/there?name=ferret#nose
 *   \_/   \______________/\_________/ \_________/ \__/
 *    |           |            |            |       |
 *  scheme   authority       path         query  fragment
 *
 * @see https://tools.ietf.org/html/rfc3986#page-16
 *
 *
 * Class HttpImmutable
 *
 * @package Keppler\Url\Schemes\Http
 */
final class HttpImmutable extends AbstractImmutable implements SchemeInterface
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME_HTTP = 'http';

    /**
     * authority = [ userinfo "@" ] host [ ":" port ]
     *
     * @var string
     */
    private $authority = '';

    /**
     * userinfo = *( unreserved / pct-encoded / sub-delims / ":" )
     *
     * @var string
     */
    private $user = '';

    /**
     * Usage is highly discouraged
     *
     * @var string
     */
    private $password = '';

    /**
     * host = IP-literal / IPv4address / reg-name
     *
     * @var string
     */
    private $host = '';

    /**
     * port = *DIGIT
     *
     * Ports can't be negative, -1 should be considered the default value and ignored
     *
     * INVALID-PORT
     *  Either the local or foreign port was improperly
     *  specified.  This should be returned if either or
     *  both of the port ids were out of range (TCP port
     *  numbers are from 1-65535), negative integers, reals or
     *  in any fashion not recognized as a non-negative integer.
     *
     * @see https://www.ietf.org/rfc/rfc1413.txt
     * @var int
     */
    private $port = -1;

    /**
     * fragment = *( pchar / "/" / "?" )
     *
     * @var string
     */
    private $fragment = '';
}

