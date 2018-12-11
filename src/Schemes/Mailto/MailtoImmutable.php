<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Mailto;

use Keppler\Url\Schemes\Mailto\Bags\MailtoImmutableQueryBag;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the mailto url is taken AS IS and will not be decoded or encoded
 * Url encoded strings WILL break the
 *
 * Default and accepted possible components of a mailto
 *
 *   mailtoURI    = "mailto:" [ to ] [ hfields ]
 *   to           = addr-spec *("," addr-spec )
 *   hfields      = "?" hfield *( "&" hfield )
 *   hfield       = hfname "=" hfvalue
 *   hfname       = *qchar
 *   hfvalue      = *qchar
 *   addr-spec    = local-part "@" domain
 *   local-part   = dot-atom-text / quoted-string
 *   domain       = dot-atom-text / "[" *dtext-no-obs "]"
 *   dtext-no-obs = %d33-90 / ; Printable US-ASCII
 *   %d94-126  ; characters not including
 *   ; "[", "]", or "\"
 *   qchar        = unreserved / pct-encoded / some-delims
 *   some-delims  = "!" / "$" / "'" / "(" / ")" / "*"
 *   / "+" / "," / ";" / ":" / "@"
 *
 * @example mailto:john@gmail.com?to=jane@gmail.com,jannis@gmail.com&cc=manny@gmail.com,july@gmail.com&bcc=travis@gmail.com,yani@gmail.com&subject=hello&body=welcome
 *
 * @see https://tools.ietf.org/html/rfc6068
 *
 * Class MailtoImmutable
 *
 * @package Keppler\Url\Schemes\Mailto
 */
final class MailtoImmutable
{
    /**
     * The default scheme for this class
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
            $path = $parsedUrl['path'];

            // If a comma is present assume that the url contains more than one email address
            // Also assume that the url isn't malformed in some way
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
