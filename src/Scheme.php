<?php
declare(strict_types=1);

namespace Keppler\Url;

use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;

/**
 * Note: This class should not make any attempt to sanitize, correct or otherwise improve the
 * information it receives. It will be take AS IS and parsed with exactly what it gets
 * any sort of errors that result from that are the concern of the caller not this class
 *
 * Class Scheme
 *
 * @package Keppler\Url\Scheme
 */
class Scheme
{
    /**
     * @param string $url
     *
     * @return MailtoImmutable
     */
    public static function mailto(string $url)
    {
        $parsed = parse_url($url);

        if (false === $parsed) {
            throw new \InvalidArgumentException('The url is malformed');
        }

        if( !isset($parsed['scheme'])) {
            throw new \InvalidArgumentException(sprintf('Unable to determine scheme for %s', $url));
        }

        if (MailtoImmutable::SCHEME_MAILTO === $parsed['scheme']) {
            return new MailtoImmutable($parsed);
        }

        throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
            MailtoImmutable::class, MailtoImmutable::SCHEME_MAILTO, $parsed['scheme']));
    }
}
