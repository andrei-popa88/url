<?php
declare(strict_types=1);

namespace Keppler\Url;

/**
 * Class AbstractUrl
 *
 * @package Keppler\Url
 */
abstract class AbstractUrl
{
    /**
     * @var string
     */
    protected $original;

    /**
     * @var string|null
     */
    public $path;

    /**
     * @var string|null
     */
    public $query;

    /**
     * @var array
     */
    protected $allowedSchemas
        = [
            'http',
            'https',
            'mailto',
        ];

    /**
     * @var
     */
    protected $schema = null;

    /**
     * @var
     */
    protected $authority = null;

    /**
     * @var
     */
    protected $fragment = null;

    /**
     * @var string|null
     */
    protected $username = null;

    /**
     * @var string|null
     */
    protected $host = null;

    /**
     * @var string|null
     */
    protected $password = null;

    /**
     * @var int|null
     */
    protected $port = null;
}
