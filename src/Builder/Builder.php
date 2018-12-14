<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\Builder\Schemes\Https\HttpsBuilder;
use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
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

    public static function https(HttpsImmutable $https): HttpsBuilder
    {
        return new HttpsBuilder($https);
    }
}
