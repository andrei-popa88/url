<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Mailto;

use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Scheme\Schemes\Mailto\Bags\MailtoImmutablePath;
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
 * @package Keppler\Url\Scheme\Schemes\MailtoImmutable
 */
class MailtoImmutable extends AbstractImmutable implements ImmutableSchemeInterface
{
    use Accessor;

    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME = 'mailto';

    /**
     * @var MailtoImmutableQuery
     */
    private $queryBag;

    /**
     * @var MailtoImmutablePath
     */
    private $pathBag;

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

        if (MailtoImmutable::SCHEME !== $parsedUrl['scheme']) {
            throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
                MailtoImmutable::class, MailtoImmutable::SCHEME,
                $parsedUrl['scheme']));
        }

        if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
            $this->queryBag = new MailtoImmutableQuery($parsedUrl['query']);
        } else {
            $this->queryBag = new MailtoImmutableQuery();
        }

        if (isset($parsedUrl['path']) && !empty($parsedUrl['path'])) {
            $this->pathBag = new MailtoImmutablePath($parsedUrl['path']);
        } else {
            $this->pathBag = new MailtoImmutablePath();
        }
    }

/////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return MailtoImmutablePath
     */
    public function getPathBag(): MailtoImmutablePath
    {
        return $this->pathBag;
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
            'scheme' => self::SCHEME,
            'path' => $this->pathBag->all(),
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
        return self::SCHEME;
    }
}
