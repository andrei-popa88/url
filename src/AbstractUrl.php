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
    protected $original = null;

    /**
     * @var
     */
    public $path;

    /**
     * @var
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
     * @var
     */
    protected $username = null;

    /**
     * @var
     */
    protected $host = null;

    /**
     * @var null
     */
    protected $password = null;

    /**
     * @var
     */
    protected $port = null;
}
