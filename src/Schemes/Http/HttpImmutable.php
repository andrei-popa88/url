<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Http;

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
final class HttpImmutable
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
    private $userinfo = '';

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
     * @var int -1
     */
    private $port = -1;
}

