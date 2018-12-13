<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme;

use Keppler\Url\Scheme\Schemes\Ftp\FtpImmutable;
use Keppler\Url\Scheme\Schemes\Http\HttpImmutable;
use Keppler\Url\Scheme\Schemes\Https\HttpsImmutable;
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
        $parsed = self::parse($url);

        if (MailtoImmutable::SCHEME === $parsed['scheme']) {
            return new MailtoImmutable($url);
        }

        throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
            MailtoImmutable::class, MailtoImmutable::SCHEME, $parsed['scheme']));
    }

    /**
     * @param string $url
     * @return HttpsImmutable
     */
    public static function https(string $url)
    {
        $parsed = self::parse($url);

        if (HttpsImmutable::SCHEME === $parsed['scheme']) {
            return new HttpsImmutable($url);
        }

        throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
            HttpsImmutable::class, HttpsImmutable::SCHEME, $parsed['scheme']));
    }

    /**
     * @param string $url
     * @return HttpImmutable
     */
    public static function http(string $url)
    {
        $parsed = self::parse($url);

        if (HttpImmutable::SCHEME === $parsed['scheme']) {
            return new HttpImmutable($url);
        }

        throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
            HttpImmutable::class, HttpImmutable::SCHEME, $parsed['scheme']));
    }

    /**
     * @param string $url
     * @return FtpImmutable
     */
    public static function ftp(string $url)
    {
        $parsed = self::parse($url);

        if (FtpImmutable::SCHEME === $parsed['scheme']) {
            return new FtpImmutable($url);
        }

        throw new \InvalidArgumentException(sprintf('Invalid scheme provided for %s, expected "%s" got "%s"',
            FtpImmutable::class, FtpImmutable::SCHEME, $parsed['scheme']));
    }

    /**
     * @param $url
     * @return mixed
     */
    private static function parse(string $url)
    {
        $parsed = parse_url($url);

        if (false === $parsed) {
            throw new \InvalidArgumentException('The url is malformed');
        }

        if( !isset($parsed['scheme'])) {
            throw new \InvalidArgumentException(sprintf('Unable to determine scheme for %s', $url));
        }

        return $parsed;
    }
}
