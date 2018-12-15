<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\Builder\Schemes\Ftp\FtpBuilder;
use Keppler\Url\Builder\Schemes\Http\HttpBuilder;
use Keppler\Url\Builder\Schemes\Https\HttpsBuilder;
use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
use Keppler\Url\Scheme\Schemes\Ftp\FtpImmutable;
use Keppler\Url\Scheme\Schemes\Http\HttpImmutable;
use Keppler\Url\Scheme\Schemes\Https\HttpsImmutable;
use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;

/**
 * Class Builder
 *
 * @package Url\Builder
 */
class Builder
{
    /**
     * @param MailtoImmutable $mailto
     *
     * @return MailtoBuilder
     * @throws \Keppler\Url\Exceptions\InvalidComponentsException
     */
    public static function mailto(MailtoImmutable $mailto): MailtoBuilder
    {
        return new MailtoBuilder($mailto);
    }

    /**
     * @param HttpsImmutable $https
     *
     * @return HttpsBuilder
     */
    public static function https(HttpsImmutable $https): HttpsBuilder
    {
        return new HttpsBuilder($https);
    }

    /**
     * @param HttpImmutable $http
     *
     * @return HttpBuilder
     */
    public static function http(HttpImmutable $http): HttpBuilder
    {
        return new HttpBuilder($http);
    }

    /**
     * @param FtpImmutable $ftp
     *
     * @return FtpBuilder
     */
    public static function ftp(FtpImmutable $ftp): FtpBuilder
    {
        return new FtpBuilder($ftp);
    }
}
