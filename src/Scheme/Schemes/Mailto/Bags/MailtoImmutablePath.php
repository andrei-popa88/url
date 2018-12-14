<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Mailto\Bags;

use Keppler\Url\Interfaces\Immutable\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Traits\Accessor;

/**
 * Class MailtoImmutablePath
 *
 * @package Keppler\Url\Scheme\Schemes\Mailto\Bags
 */
class MailtoImmutablePath extends AbstractImmutable implements ImmutableBagInterface
{
    use Accessor;

    /**
     * The path can be either a string or a comma separated value of strings
     *
     * @example mailto:john@gmail.com,test@gmail.com is an array
     * @example mailto:john@gmail.com is a string
     *
     * @var string | array
     */
    private $path = [];

    /**
     * @var string
     */
    private $raw = '';

    /**
     * MailtoImmutablePath constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        $this->raw = $raw;
        if (!empty(trim($raw))) {
            // If a comma is present assume that the url contains more than one email address
            // Also assume that the url isn't malformed in some way
            // No validation of emails will occur, it's the job of the caller to do that
            if (false !== strpos($raw, ',')) {
                $this->path = explode(',', $raw);
            } else {
                $this->path = [];
            }
        }
    }

/////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////
    /**
     * @return string
     */
    public function firstInPath(): ?string
    {
        if (is_array($this->path)) {
            return $this->firstIn($this->path);
        }

        return $this->path;
    }

    /**
     * @return string
     */
    public function lastInPath(): ?string
    {
        if (is_array($this->path)) {
            return $this->lastIn($this->path);
        }

        return $this->path;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function hasInPath(string $value): bool
    {
        if (is_array($this->path)) {
            return $this->hasValueIn($this->path, $value);
        }

        return $value === $this->path;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

    /**
     * @return string | array
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }
}
