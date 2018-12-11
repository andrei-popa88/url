<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Ftp;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the ftp url is taken AS IS and will not be decoded or encoded
 * url encoded strings WILL result in errors
 *
 * ftpurl = "ftp://" login [ "/" fpath [ ";type=" ftptype ]]
 *
 * ftp://[user[:password]@]host[:port]/url-path
 *
 * @see https://tools.ietf.org/html/rfc1738
 *
 * Class FtpImmutable
 *
 * @package Keppler\Url\Schemes\Ftp
 */
final class FtpImmutable
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME_FTP = 'ftp';

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

}
