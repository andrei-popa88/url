<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Http\Bags;

use Keppler\Url\Scheme\Interfaces\BagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;

/**
 * Class HttpImmutablePathBag
 * @package Keppler\Url\Schemes\Http\Bags
 */
class HttpImmutablePathBag extends AbstractImmutable implements BagInterface
{
    /**
     *  path = path-abempty    ; begins with "/" or is empty
     *
     *
     *  / path-absolute   ; begins with "/" but not "//"
     *  / path-noscheme   ; begins with a non-colon segment
     *  / path-rootless   ; begins with a segment
     *  / path-empty      ; zero characters
     *
     *  path-abempty  = *( "/" segment )
     *  path-absolute = "/" [ segment-nz *( "/" segment ) ]
     *  path-noscheme = segment-nz-nc *( "/" segment )
     *  path-rootless = segment-nz *( "/" segment )
     *  path-empty    = 0<pchar>
     *  segment       = *pchar
     *  segment-nz    = 1*pchar
     *  segment-nz-nc = 1*( unreserved / pct-encoded / sub-delims / "@" )
     *  ; non-zero-length segment without any colon ":"
     *
     *  pchar         = unreserved / pct-encoded / sub-delims / ":" / "@
     *
     * @see https://tools.ietf.org/html/rfc3986#page-22
     *
     * @var array
     */
    private $path = [];
}
