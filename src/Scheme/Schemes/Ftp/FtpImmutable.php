<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Ftp;

use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Scheme\Schemes\Ftp\Bags\FtpImmutablePath;

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
class FtpImmutable extends AbstractImmutable implements ImmutableSchemeInterface
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME = 'ftp';

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
     * @var FtpImmutablePath
     */
    private $pathBag;

    /**
     * @var string
     */
    private $raw;

    /**
     * MailtoImmutable constructor.
     * @param $url
     */
    public function __construct(string $url)
    {
        $this->raw = $url;

        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            $this->pathBag = new FtpImmutablePath($parsedUrl['path']);
        } else {
            $this->pathBag = new FtpImmutablePath();
        }

        if(isset($parsedUrl['user']) && !empty($parsedUrl['user'])) {
            $this->user = $parsedUrl['user'];
        }

        if(isset($parsedUrl['host']) && !empty($parsedUrl['host'])) {
            $this->host = $parsedUrl['host'];
        }

        if(isset($parsedUrl['port']) && !empty($parsedUrl['port'])) {
            $this->port = $parsedUrl['port'];
        }

        if(isset($parsedUrl['pass']) && !empty($parsedUrl['pass'])) {
            $this->password = $parsedUrl['pass'];
        }
    }

//////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return FtpImmutablePath
     */
    public function getPathBag(): FtpImmutablePath
    {
        return $this->pathBag;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): ?int
    {
        return -1 === $this->port ? null : $this->port;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATIOM  ///
////////////////////////////////

    /**
     * Returns all the components of the scheme including
     *  any bags in the form of an array
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'scheme' => self::SCHEME,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => -1 === $this->port ? '' : $this->port,
            'path' => $this->pathBag->all(),
        ];
    }

    /**
     * Return the raw unaltered url
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * Returns the scheme associated with the class
     *
     * @return string
     */
    public function getScheme(): string
    {
        return self::SCHEME;
    }
}
