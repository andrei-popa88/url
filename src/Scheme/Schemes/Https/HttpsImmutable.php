<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Https;

use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Scheme\Schemes\Https\Bags\HttpsImmutablePath;
use Keppler\Url\Scheme\Schemes\Https\Bags\HttpsImmutableQuery;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the https url is taken AS IS and will not be decoded or encoded
 * url encoded strings WILL result in errors
 *
 *  userinfo     host        port
 * ┌─┴────┐ ┌────┴────────┐ ┌┴┐
 *  https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top
 *  └─┬─┘ └───────┬────────────────────┘└─┬─────────────┘└──┬───────────────────────┘└┬─┘
 * scheme     authority                 path              query                      fragment
 *
 * @see https://tools.ietf.org/html/rfc3986#page-16
 *
 *
 * Class HttpsImmutable
 *
 * @package Keppler\Url\Schemes\HttpsImmutable
 */
class HttpsImmutable extends AbstractImmutable implements ImmutableSchemeInterface
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
     * @var HttpsImmutableQuery
     */
    private $queryBag;

    /**
     * @var HttpsImmutablePath
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

        $this->setAuthority($parsedUrl);

        if(isset($parsedUrl['fragment'])) {
            if(false !== strpos($parsedUrl['fragment'], '#')) {
                // get only the first fragment
                $this->fragment = explode('#', $parsedUrl['fragment'])[0];
            }else {
                $this->fragment = $parsedUrl['fragment'];
            }
        }

        if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $this->queryBag = new HttpsImmutableQuery($parsedUrl['query']);
        } else {
            $this->queryBag = new HttpsImmutableQuery();
        }

        if (isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            $this->pathBag = new HttpsImmutablePath($parsedUrl['path']);
        } else {
            $this->pathBag = new HttpsImmutablePath();
        }
    }

///////////////////////////
/// PRIVATE FUNCTIONS  ///
/////////////////////////

    /**
     * @param array $parsedUrl
     */
    private function setAuthority(array $parsedUrl)
    {
        $authority = '';

        if(isset($parsedUrl['user'])) {
            $authority .= $parsedUrl['user'];
            $this->user = $parsedUrl['user'];
        }

        if(isset($parsedUrl['pass'])) {
            $authority .= ':' . $parsedUrl['pass'];
            $this->password = $parsedUrl['pass'];
        }

        if(isset($parsedUrl['host'])) {
            if(!empty($this->user) || !empty($this->password)) {
                $authority .= '@';
            }
            $authority .= $parsedUrl['host'];
            $this->host = $parsedUrl['host'];
        }

        if(isset($parsedUrl['port'])) {
            $authority .= ':' . $parsedUrl['port'];
            $this->port = $parsedUrl['port'];
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
     * @return HttpsImmutableQuery
     */
    public function getQueryBag(): HttpsImmutableQuery
    {
        return $this->queryBag;
    }

    /**
     * @return HttpsImmutablePath
     */
    public function getPathBag(): HttpsImmutablePath
    {
        return $this->pathBag;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

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
            'port' => $this->port === -1 ? null : $this->port,
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
    public function getScheme(): string
    {
        return self::SCHEME;
    }
}
