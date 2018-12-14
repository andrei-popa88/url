<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Https;

use Keppler\Url\Builder\Schemes\Https\Bags\HttpsMutablePath;
use Keppler\Url\Builder\Schemes\Https\Bags\HttpsMutableQuery;
use Keppler\Url\Interfaces\Mutable\MutableSchemeInterface;

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
     * HttpsImmutable constructor.
     * @param $url
     */
    public function __construct(string $url)
    {
        $this->raw = $url;

        $parsedUrl = parse_url($url);

        $this->setInitialAuthority($parsedUrl);

        if (isset($parsedUrl['fragment'])) {
            if (false !== strpos($parsedUrl['fragment'], '#')) {
                // get only the first fragment
                $this->fragment = explode('#', $parsedUrl['fragment'])[0];
            } else {
                $this->fragment = $parsedUrl['fragment'];
            }
        }

        if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $this->queryBag = new HttpsMutablePath($parsedUrl['query']);
        } else {
            $this->queryBag = new HttpsMutablePath();
        }

        if (isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            $this->pathBag = new HttpsMutablePath($parsedUrl['path']);
        } else {
            $this->pathBag = new HttpsMutablePath();
        }
    }

///////////////////////////
/// PRIVATE FUNCTIONS  ///
/////////////////////////

    /**
     * @param array $parsedUrl
     */
    private function setInitialAuthority(array $parsedUrl): void
    {
        $authority = '';

        if (isset($parsedUrl['user'])) {
            $authority .= $parsedUrl['user'];
            $this->user = $parsedUrl['user'];
        }

        if (isset($parsedUrl['pass'])) {
            $authority .= ':'.$parsedUrl['pass'];
            $this->password = $parsedUrl['pass'];
        }

        if (isset($parsedUrl['host'])) {
            $authority .= '@'.$parsedUrl['host'];
            $this->host = $parsedUrl['host'];
        }

        if (isset($parsedUrl['port'])) {
            $authority .= $parsedUrl['port'];
            $this->port = $parsedUrl['port'];
        }

        $this->authority = $authority;
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
            $authority .= $this->port;
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
     * @return HttpsBuilder
     */
    public function setPort(int $port): self
    {
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
        // mailtoURL  =  "mailto:" [ to ] [ headers ]
        // to         =  #mailbox
        // headers    =  "?" header *( "&" header )
        // header     =  hname "=" hvalue
        // hname      =  *urlc
        // hvalue     =  *urlc

        $url = self::SCHEME.':';
        $commaEncoded = '%2C';

        // The path ca be either a single string value or an array of values
        if (is_array($this->path)) {
            foreach ($this->path as $email) {
                if ($urlEncode) {
                    $url .= $email.$commaEncoded;
                } else {
                    $url .= $email.',';
                }
            }
            $url = rtrim($url, ',');
        } else {
            $url .= $this->path;
        }

        if ($urlEncode) {
            $url .= $this->queryBag->encoded();
        } else {
            $url .= $this->queryBag->raw();
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