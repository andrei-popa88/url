<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Mailto;

use Keppler\Url\Schemes\Mailto\Bags\MailtoImmutableQueryBag;

/**
 * Class MailtoImmutable
 *
 * @package Keppler\Url\Schemes\Mailto
 */
final class MailtoImmutable
{
    /**
     * This property should never be set by a setter
     * it must always be constant however for pretty sake
     * it's left as a class property rather than a constant
     * which can be accessed outside of a class in less pretty way
     *
     * @var string
     */
    const SCHEME_MAILTO = 'mailto';

    /**
     * @var MailtoImmutableQueryBag | null
     */
    private $queryBag = null;

    /**
     * The path can be either a string or an array
     *
     * @example mailto:john@gmail.com,test@gmail.com is an array
     * @example mailto:john@gmail.com is a string
     *
     * @var null | string | array
     */
    private $path = null;

    /**
     * MailtoImmutable constructor.
     *
     * @param array $parsedUrl
     */
    public function __construct(array $parsedUrl)
    {
        if(isset($parsedUrl['path']) && !empty(trim($parsedUrl['path']))){
            $path = urldecode($parsedUrl['path']);
            // If commas are present explode
            // That's assuming nobody actually managed to insert a comma into the email address
            // No validation of emails will occur, it's the job of the caller to do that
            if(false !== strpos($path, ',')) {
                $this->path = explode(',', $path);
            }else {
                $this->path = $path;
            }
        }

        if(isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $this->queryBag = new MailtoImmutableQueryBag($parsedUrl['query']);
        }
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return self::SCHEME_MAILTO;
    }

    /**
     * @return MailtoImmutableQueryBag|null
     */
    public function getQueryBag(): ?MailtoImmutableQueryBag
    {
        return $this->queryBag;
    }

    /**
     * @return array|null|string
     */
    public function getPath()
    {
        return $this->path;
    }
}
