<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\AbstractUrl;
use Keppler\Url\Builder\Bags\PathBag;
use Keppler\Url\Builder\Bags\QueryBag;
use Keppler\Url\Parser\Exceptions\SchemaNotSupportedException;
use Keppler\Url\Parser\Parser;

/**
 * Class Builder
 *
 * @package Url\Builder
 */
class Builder extends AbstractUrl
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
     * @param Parser $parser
     *
     * @return Builder
     */
    public static function from(Parser $parser): self
    {
        $self = new self;
        $self->query = new QueryBag();
        $self->path = new PathBag();

        $self->original = $parser->getOriginal();
        $self->schema = $parser->getSchema();
        $self->authority = $parser->getAuthority();
        $self->fragment = $parser->getFragment();
        $self->username = $parser->getUsername();
        $self->host = $parser->getHost();
        $self->password = $parser->getPassword();
        $self->port = $parser->getPort();

        $self->path->setPathComponents($parser->path->all());
        $self->query->setQueryComponents($parser->query->all());
        return $self;
    }
//$toParse = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';

    /**
     * @param string $scheme
     *
     * @return Builder
     * @throws SchemaNotSupportedException
     */
    public function setScheme(string $scheme): self
    {
        if(!in_array($scheme, $this->allowedSchemas)) {
            throw new SchemaNotSupportedException("The scheme is not supported");
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
}
