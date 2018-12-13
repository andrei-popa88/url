<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Mailto;

use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Scheme\Schemes\Mailto\Bags\MailtoImmutableQuery;
use Keppler\Url\Traits\Accessor;

/**
 * Note that the following class makes no assumption regarding url encoding
 * the mailto url is taken AS IS and will not be decoded or encoded
 * Url encoded strings WILL result in errors
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
 * @package Keppler\Url\Scheme\Schemes\Mailto
 */
class MailtoImmutable extends AbstractImmutable implements ImmutableSchemeInterface
{
    use Accessor;

    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME_MAILTO = 'mailto';

    /**
     * @var MailtoImmutableQuery
     */
    private $queryBag;

    /**
     * The path can be either a string or a comma separated value of strings
     *
     * @example mailto:john@gmail.com,test@gmail.com is an array
     * @example mailto:john@gmail.com is a string
     *
     * @var string | array
     */
    private $path = '';

    /**
     * @var string
     */
    private $raw = '';

    /**
     * MailtoImmutable constructor.
     * @param $url
     */
    public function __construct(string $url)
    {
        $this->raw = $url;

        $parsedUrl = parse_url($url);

        if(isset($parsedUrl['path']) && !empty(trim($parsedUrl['path']))){
            // If a comma is present assume that the url contains more than one email address
            // Also assume that the url isn't malformed in some way
            // No validation of emails will occur, it's the job of the caller to do that
            if(false !== strpos($parsedUrl['path'], ',')) {
                $this->path = explode(',', $parsedUrl['path']);
            }else {
                $this->path = $parsedUrl['path'];
            }
        }

        if(isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $this->queryBag = new MailtoImmutableQuery($parsedUrl['query']);
        } else {
            $this->queryBag = new MailtoImmutableQuery();
        }
    }

///////////////////////
/// PATH FUNCTIONS  ///
///////////////////////

    /**
     * @return string
     */
    public function firstInPath(): string
    {
        if(is_array($this->path)) {
            return $this->firstIn($this->path);
        }

        return $this->path;
    }

    /**
     * @return string
     */
    public function lastInPath(): string
    {
        if(is_array($this->path)) {
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
        if(is_array($this->path)){
            return $this->hasValueIn($this->path, $value);
        }

        return $value === $this->path;
    }

/////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return string | array
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return MailtoImmutableQuery
     */
    public function getQueryBag(): MailtoImmutableQuery
    {
        return $this->queryBag;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

    /**
     * @inheritdoc
     */
    public function all(): array
    {
        return [
            'scheme' => self::SCHEME_MAILTO,
            'path' => $this->path,
            'query' => $this->queryBag->all(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return self::SCHEME_MAILTO;
    }
}
