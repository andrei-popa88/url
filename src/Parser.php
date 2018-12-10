<?php
declare(strict_types=1);

namespace Keppler\Url\Parser;

use Keppler\Url\Schemes\Mailto\MailtoImmutable;

/**
 * Class Parser
 *
 * @package Keppler\Url\Parser
 */
class Parser
{
    public static function fromMailto(string $url)
    {
        $parsed = parse_url($url);

        if(false === $parsed || !isset($parsed['scheme'])) {
            throw new \InvalidArgumentException("The url is malformed");
        }

        if(MailtoImmutable::SCHEME_MAILTO === $parsed['scheme']) {
            return new MailtoImmutable($parsed);
        }

        throw new \InvalidArgumentException(sprintf("Invalid scheme provided for %s, expected \"%s\" got \"%s\"", MailtoImmutable::class, MailtoImmutable::SCHEME_MAILTO, $parsed['scheme']));
    }


}
