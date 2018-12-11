<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\AbstractUrl;
use Keppler\Url\Builder\Bags\PathBag;
use Keppler\Url\Builder\Bags\QueryBag;
use Keppler\Url\Exceptions\SchemeNotSupportedException;
use Keppler\Url\Parser\Parser;

/**
 * Class Builder
 *
 * @package Url\Builder
 */
class Builder extends AbstractUrl
{
    /**
     * @var array
     */
    private $allowedSchemas
        = [
            'http',
            'https',
            'mailto',
        ];

    /**
     * @var PathBag
     */
    public $path;

    /**
     * @var QueryBag
     */
    public $query;

    /**
     * Builder constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->query = new QueryBag();
        $this->path = new PathBag();

        $this->original = $parser->getOriginal();
        $this->schema = $parser->getSchema();
        $this->authority = $parser->getAuthority();
        $this->fragment = $parser->getFragment();
        $this->username = $parser->getUsername();
        $this->host = $parser->getHost();
        $this->password = $parser->getPassword();
        $this->port = $parser->getPort();

        $this->path->setPathComponents($parser->path->all());
        $this->query->setQueryComponents($parser->query->all());

    }

    /**
     * @param string $scheme
     *
     * @return Builder
     * @throws SchemeNotSupportedException
     */
    public function setScheme(string $scheme): self
    {
        if ( ! in_array($scheme, $this->allowedSchemas)) {
            throw new SchemeNotSupportedException("The scheme is not supported");
        }

        $this->schema = $scheme;

        return $this;
    }

    /**
     * @param string $fragment
     *
     * @return Builder
     */
    public function setFragment(string $fragment): self
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * @param string $username
     *
     * @return Builder
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return Builder
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return Builder
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return Builder
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Builds the authority by appending username:password@host:port
     */
    private function buildAuthority(): string
    {
        $authority = '';

        if (null !== $this->username) {
            $authority .= $this->username;

            if (null !== $this->password) {
                $authority .= ':'.$this->password.'@';
            } else {
                $authority .= '@';
            }
        }

        $authority .= $this->host;

        if (null !== $this->port) {
            $authority .= ':'.$this->port;
        }

        return $authority;
    }

    /**
     * @param bool $withTrailingSlash
     *
     * @return string
     */
    public function getUrl(bool $withTrailingSlash = true): string
    {
        $url = '';

        if (null === $this->schema || null === $this->host) {
            throw new \LogicException("At least the schema and the host must be present.");
        }

        $url .= $this->schema.'://';
        $url .= $this->buildAuthority();
        $url .= $this->path->raw($withTrailingSlash);
        $url .= $this->query->raw();
        if (null !== $this->fragment) {
            $url .= '#'.$this->fragment;
        }

        return $url;
    }
}
