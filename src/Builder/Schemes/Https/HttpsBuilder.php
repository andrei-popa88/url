<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Https;

use Keppler\Url\Builder\Schemes\Https\Bags\HttpsMutablePath;
use Keppler\Url\Builder\Schemes\Https\Bags\HttpsMutableQuery;
use Keppler\Url\Interfaces\Mutable\MutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\Https\HttpsImmutable;

/**
 * Class HttpsBuilder
 * @package Keppler\Url\Builder\Schemes\Https
 */
class HttpsBuilder implements MutableSchemeInterface
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME = 'https';

    /**
     * authority = [ userinfo "@" ] host [ ":" port ]
     *
     * -- An optional authority component preceded by two slashes (//), comprising:
     *
     *   - An optional userinfo subcomponent that may consist of a user name and an optional password preceded
     *  by a colon (:), followed by an at symbol (@). Use of the format username:password in the userinfo
     *  subcomponent is deprecated for security reasons. Applications should not render as clear text any data
     *  after the first colon (:) found within a userinfo subcomponent unless the data after the colon
     *  is the empty string (indicating no password).
     *
     *  - An optional host subcomponent, consisting of either a registered name (including but not limited to a hostname),
     *  or an IP address. IPv4 addresses must be in dot-decimal notation, and IPv6 addresses must be enclosed in brackets ([]).[10][c]
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

    /**
     * @var HttpsMutableQuery
     */
    private $queryBag;

    /**
     * @var HttpsMutablePath
     */
    private $pathBag;

    /**
     * @var string
     */
    private $raw = '';

    /**
     * HttpsBuilder constructor.
     *
     * @param HttpsImmutable $https
     */
    public function __construct(HttpsImmutable $https)
    {
        $this->pathBag = new HttpsMutablePath();
        $this->queryBag = new HttpsMutableQuery();
        $this->populate($https);

        $this->authority = $https->getAuthority();
        $this->user = $https->getUser();
        $this->password = $https->getPassword();
        $this->host = $https->getHost();
        $this->port = -1 === $https->getPort() ? -1 : $https->getPort();
        $this->fragment = $https->getFragment();
    }

///////////////////////////
/// PRIVATE FUNCTIONS  ///
/////////////////////////
    /**
     * @param HttpsImmutable $https
     */
    private function populate(HttpsImmutable $https): void
    {
        foreach ($https->getPathBag()->all() as $key => $value){
            $this->pathBag->set($key, $value);
        }

        foreach ($https->getQueryBag()->all() as $key => $value){
            $this->queryBag->set($key, $value);
        }
    }

    /**
     * Recreate the authority
     */
    private function buildAuthority(): void
    {
        $authority = '';

        if (!empty($this->user)) {
            $authority .= $this->user;
        }

        if (!empty($this->password)) {
            $authority .= ':'.$this->password;
        }

        if (!empty($this->host)) {
            $authority .= '@'.$this->host;
        }

        if (-1 !== $this->port) {
            $authority .= ':' . $this->port;
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
     * @return HttpsMutableQuery
     */
    public function getQueryBag(): HttpsMutableQuery
    {
        return $this->queryBag;
    }

    /**
     * @return HttpsMutablePath
     */
    public function getPathBag(): HttpsMutablePath
    {
        return $this->pathBag;
    }

//////////////////////////
/// SETTER FUNCTIONS  ///
////////////////////////

    /**
     * @param string $user
     * @return HttpsBuilder
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
     * @return HttpsBuilder
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
     * @return HttpsBuilder
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
     * @return HttpsBuilder
     * @throws \LogicException
     */
    public function setPort(int $port): self
    {
        if(abs($port) !== $port) {
            throw new \LogicException('Ports cannot be negative');
        }

        $this->port = $port;
        // Rebuild the authority
        $this->buildAuthority();

        return $this;
    }

    /**
     * @param string $fragment
     * @return HttpsBuilder
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

        if(!empty($this->fragment)) {
            $url .= '#' . $this->fragment;
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
            'scheme' => self::SCHEME,
            'authority' => $this->authority,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => -1 === $this->port ? null : $this->port,
            'query' => $this->queryBag->all(),
            'path' => $this->pathBag->all(),
            'fragment' => $this->fragment,
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
    public function getScheme()
    {
        return self::SCHEME;
    }
}