<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Http;

use Keppler\Url\Builder\Schemes\Http\Bags\HttpMutablePath;
use Keppler\Url\Builder\Schemes\Http\Bags\HttpMutableQuery;
use Keppler\Url\Interfaces\Mutable\MutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\Http\HttpImmutable;

/**
 * Class HttpBuilder
 *
 * @package Keppler\Url\Builder\Schemes\Https
 */
class HttpBuilder implements MutableSchemeInterface
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME = 'http';

    /**
     * authority = [ userinfo "@" ] host [ ":" port ]
     *
     * -- An optional authority component preceded by two slashes (//),
     * comprising:
     *
     *   - An optional userinfo subcomponent that may consist of a user name
     *   and an optional password preceded by a colon (:), followed by an at
     *   symbol (@). Use of the format username:password in the userinfo
     *   subcomponent is deprecated for security reasons. Applications should
     *   not render as clear text any data after the first colon (:) found
     *   within a userinfo subcomponent unless the data after the colon is the
     *   empty string (indicating no password).
     *
     *  - An optional host subcomponent, consisting of either a registered name
     *  (including but not limited to a hostname), or an IP address. IPv4
     *  addresses must be in dot-decimal notation, and IPv6 addresses must be
     *  enclosed in brackets ([]).[10][c]
     *
     *   - An optional port subcomponent preceded by a colon (:).
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
     * fragment = *( pchar / "/" / "?" )
     *
     * @var string
     */
    private $fragment = '';

    /**
     * @var HttpMutableQuery
     */
    private $queryBag;

    /**
     * @var HttpMutablePath
     */
    private $pathBag;

    /**
     * HttpBuilder constructor.
     *
     * @param HttpImmutable $http
     */
    public function __construct(HttpImmutable $http = null)
    {
        $this->pathBag = new HttpMutablePath();
        $this->queryBag = new HttpMutableQuery();

        if(null !== $http) {
            $this->populate($http);

            $this->authority = $http->getAuthority();
            $this->user = $http->getUser();
            $this->password = $http->getPassword();
            $this->host = $http->getHost();
            $this->port = null === $http->getPort() ? -1 : $http->getPort();
            $this->fragment = $http->getFragment();
        }
    }

    ///////////////////////////
    /// PRIVATE FUNCTIONS  ///
    /////////////////////////
    /**
     * @param HttpImmutable $http
     */
    private function populate(HttpImmutable $http): void
    {
        foreach ($http->getPathBag()->all() as $key => $value) {
            $this->pathBag->set($key, $value);
        }

        foreach ($http->getQueryBag()->all() as $key => $value) {
            $this->queryBag->set($key, $value);
        }
    }

    /**
     * Recreate the authority
     */
    private function buildAuthority(): void
    {
        $authority = '';

        if ( ! empty($this->user)) {
            $authority .= $this->user;
        }

        if ( ! empty($this->password)) {
            $authority .= ':'.$this->password;
        }

        if ( ! empty($this->host)) {
            $authority .= '@'.$this->host;
        }

        if (-1 !== $this->port) {
            $authority .= ':'.$this->port;
        }

        $this->authority = $authority;
    }

    //////////////////////////
    /// GETTER FUNCTIONS  ///
    ////////////////////////

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        return $this->authority;
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
     * @return int|null
     */
    public function getPort(): ?int
    {
        return -1 === $this->port ? null : $this->port;
    }

    /**
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @return HttpMutableQuery
     */
    public function getQueryBag(): HttpMutableQuery
    {
        return $this->queryBag;
    }

    /**
     * @return HttpMutablePath
     */
    public function getPathBag(): HttpMutablePath
    {
        return $this->pathBag;
    }

    //////////////////////////
    /// SETTER FUNCTIONS  ///
    ////////////////////////

    /**
     * @param string $user
     *
     * @return HttpBuilder
     */
    public function setUser(string $user): self
    {
        $this->user = $user;
        // Rebuild the authority
        $this->buildAuthority();

        return $this;
    }

    /**
     * @param string $password
     *
     * @return HttpBuilder
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        // Rebuild the authority
        $this->buildAuthority();

        return $this;
    }

    /**
     * @param string $host
     *
     * @return HttpBuilder
     */
    public function setHost(string $host): self
    {
        $this->host = $host;
        // Rebuild the authority
        $this->buildAuthority();

        return $this;
    }

    /**
     * @param int $port
     *
     * @return HttpBuilder
     * @throws \LogicException
     */
    public function setPort(int $port): self
    {
        if (abs($port) !== $port) {
            throw new \LogicException('Ports cannot be negative');
        }

        $this->port = $port;
        // Rebuild the authority
        $this->buildAuthority();

        return $this;
    }

    /**
     * @param string $fragment
     *
     * @return HttpBuilder
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;

        return $this;
    }

    /////////////////////////////////
    /// INTERFACE IMPLEMENTATION  ///
    /////////////////////////////////

    /**
     * @param bool $urlEncode
     *
     * @return string
     */
    public function build(bool $urlEncode = false): string
    {
        $url = self::SCHEME.'://';

        $url .= $this->authority;

        if ($urlEncode) {
            $url .= $this->pathBag->encoded();
            $url .= $this->queryBag->encoded();
        } else {
            $url .= $this->pathBag->raw();
            $url .= $this->queryBag->raw();
        }

        if ( ! empty($this->fragment)) {
            $url .= '#'.$this->fragment;
        }

        return $url;
    }

    /**
     * Returns all the components of the scheme including
     *  any bags in the form of an array
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'scheme'    => self::SCHEME,
            'authority' => $this->authority,
            'user'      => $this->user,
            'password'  => $this->password,
            'host'      => $this->host,
            'port'      => -1 === $this->port ? null : $this->port,
            'query'     => $this->queryBag->all(),
            'path'      => $this->pathBag->all(),
            'fragment'  => $this->fragment,
        ];
    }

    /**
     * Return the raw unaltered url
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->build();
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