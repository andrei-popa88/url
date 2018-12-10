<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Models;

use Keppler\Url\AbstractUrl;

abstract class AbstractUrlModel extends AbstractUrl
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