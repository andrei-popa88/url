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
     * @example mailto:john@gmail.com,test@gmail.com
     * @example mailto:john@gmail.com
     *
     * @var array
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
     * @return null|string
     */
    public function first(): ?string
    {
        return $this->firstInPath($this->path);
    }

    /**
     * @return null|string
     */
    public function last(): ?string
    {
        return $this->lastInPath($this->path);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function hasInPath(string $value): bool
    {
        return $this->hasValueIn($this->path, $value);
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

    /**
     * @return array
     */
    public function getPath(): array
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
