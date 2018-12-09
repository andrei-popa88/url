<?php
declare(strict_types=1);

namespace Keppler\Url\Parser;

use Keppler\Url\AbstractUrl;
use Keppler\Url\Parser\Bags\PathBag;
use Keppler\Url\Parser\Bags\QueryBag;
use Keppler\Url\Parser\Exceptions\MalformedUrlException;
use Keppler\Url\Parser\Exceptions\SchemaNotSupportedException;

/**
 * Immutable Class Parser
 *
 * @package Url\Parser
 */
class Parser extends AbstractUrl
{
    /**
     * @var PathBag
     */
    public $path;

    /**
     * @var QueryBag
     */
    public $query;


    /**
     * @param string $url
     *
     * @return Parser
     * @throws MalformedUrlException
     * @throws SchemaNotSupportedException
     */
    public static function from(string $url): self
    {
        $self = new self();
        $schemaFromUrl = parse_url($url);

        if ( ! isset($schemaFromUrl['scheme'])) {
            throw new MalformedUrlException("Missing scheme");
        }

        $schemaFromUrl = $schemaFromUrl['scheme'];
        if ( ! in_array($schemaFromUrl, $self->allowedSchemas)) {
            throw new SchemaNotSupportedException(vsprintf("Scheme not allowed. Only %s, %s, and %s are supported. If you need additional schemas extend this class and roll your own implementation.",
                $self->allowedSchemas));
        }

        $self->query = new QueryBag();
        $self->path = new PathBag();
        $self->parseUrl($url);
        $self->original = $url;

        return $self;
    }

    /**
     * @param string $url
     */
    private function parseUrl(string $url): void
    {
        $parsedUrl = parse_url($url);

        $this->schema = $parsedUrl['scheme'] ?? null;
        $this->host = $parsedUrl['host'] ?? null;
        $this->port = $parsedUrl['port'] ?? null;
        $this->username = $parsedUrl['user'] ?? null;
        $this->password = $parsedUrl['pass'] ?? null;
        $this->buildAuthority();

        if (isset($parsedUrl['fragment'])) {
            $this->buildFragment($parsedUrl['fragment']);
        }

        ! isset($parsedUrl['path'])
            ?: $this->path->buildPathComponents($parsedUrl['path']);
        ! isset($parsedUrl['query'])
            ?: $this->query->buildQueryComponents($parsedUrl['query']);
    }

    /**
     * @param null|string $fragments
     */
    private function buildFragment(?string $fragments): void
    {
        if (null === $fragments) {
            $this->fragment = null;
        }

        // Explode by # and get ONLY the first entry regardless of how many there are
        $fragments = explode('#', $fragments);
        $this->fragment = $fragments[0];
    }

    /**
     * Builds the authority by appending username:password@host:port
     */
    private function buildAuthority(): void
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

        $this->authority = $authority;
    }

    /**
     * @return null|string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return [
            'schema'    => $this->schema,
            'host'      => $this->host,
            'authority' => $this->authority,
            'path'      => $this->path->all(),
            'query'     => $this->query->all(),
            'fragment'  => $this->fragment,
            'username'  => $this->username,
            'password'  => $this->password,
            'port'      => $this->port,
        ];
    }

    /**
     * @return null|string
     */
    public function getUserInfo(): ?string
    {
        if (null === $this->username) {
            return null;
        }

        $userInfo = $this->username;

        if (null !== $this->password) {
            $userInfo .= ':'.$this->password;
        }

        return $userInfo;
    }

    /**
     * @return null|string
     */
    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * @return null|string
     */
    public function getAuthority(): ?string
    {
        return $this->authority;
    }

    /**
     * @return null|string
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return null|string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

}
