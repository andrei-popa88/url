<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Ftp;

use Keppler\Url\Builder\Schemes\Ftp\Bags\FtpMutablePath;
use Keppler\Url\Interfaces\Mutable\MutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\Ftp\Bags\FtpImmutablePath;
use Keppler\Url\Scheme\Schemes\Ftp\FtpImmutable;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the ftp url is taken AS IS and will not be decoded or encoded
 * url encoded strings WILL result in errors
 *
 * ftpurl = "ftp://" login [ "/" fpath [ ";type=" ftptype ]]
 *
 * ftp://[user[:password]@]host[:port]/url-path
 *
 * @see     https://tools.ietf.org/html/rfc1738
 *
 * Class FtpImmutable
 *
 * @package Keppler\Url\Schemes\Ftp
 */
class FtpBuilder implements MutableSchemeInterface
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
     * Ports can't be negative, -1 should be considered the default value and
     * ignored
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
     * @var FtpMutablePath
     */
    private $pathBag;

    /**
     * FtpBuilder constructor.
     *
     * @param FtpImmutable|null $ftp
     */
    public function __construct(FtpImmutable $ftp = null)
    {
        $this->pathBag = new FtpMutablePath();

        if (null !== $ftp) {
            $this->populate($ftp);

            $this->user = $ftp->getUser();
            $this->password = $ftp->getPassword();
            $this->host = $ftp->getHost();
            $this->port = null === $ftp->getPort() ? -1 : $ftp->getPort();
        }
    }

    ///////////////////////////
    /// PRIVATE FUNCTIONS  ///
    /////////////////////////
    /**
     * @param FtpImmutable $ftp
     */
    private function populate(FtpImmutable $ftp): void
    {
        foreach ($ftp->getPathBag()->all() as $key => $value) {
            $this->pathBag->set($key, $value);
        }
    }

    //////////////////////////
    /// GETTER FUNCTIONS  ///
    ////////////////////////

    /**
     * @return FtpMutablePath
     */
    public function getPathBag(): FtpMutablePath
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
    public function getPort(): int
    {
        return $this->port;
    }

    //////////////////////////
    /// SETTER FUNCTIONS  ///
    ////////////////////////

    /**
     * @param string $user
     *
     * @return FtpBuilder
     */
    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return FtpBuilder
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return FtpBuilder
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return FtpBuilder
     * @throws \LogicException
     */
    public function setPort(int $port): self
    {
        if (abs($port) !== $port) {
            throw new \LogicException('Ports cannot be negative');
        }

        $this->port = $port;

        return $this;
    }

    //////////////////////////////////
    /// INTERFACE IMPLEMENTATION  ///
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
            'scheme'   => self::SCHEME,
            'user'     => $this->user,
            'password' => $this->password,
            'host'     => $this->host,
            'port'     => -1 === $this->port ? '' : $this->port,
            'path'     => $this->pathBag->all(),
        ];
    }

    /**
     * Return the raw unaltered url
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->build(false);
    }

    /**
     * @return string
     */
    public function encoded(): string
    {
        return $this->build(true);
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

    /**
     * @param bool $urlEncode
     *
     * @return string
     */
    public function build(bool $urlEncode = false): string
    {
        // ftp://[user[:password]@]host[:port]/url-path

        $url = self::SCHEME.'://';

        if (!empty($this->user)) {
            $url .= $this->user;
            if (!empty($this->password)) {
                $url .= ':'.$this->password;
            }

            $url .= '@';
        }

        $url .= $this->host;

        if (-1 !== $this->port) {
            $url .= ':'.$this->port;
        }

        if ($urlEncode) {
            $url .= $this->pathBag->encoded();
        } else {
            $url .= $this->pathBag->raw();
        }

        return $url;
    }
}
