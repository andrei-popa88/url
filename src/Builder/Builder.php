<?php
declare(strict_types=1);

namespace Keppler\Url\Builder;

use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
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
     */
    public static function mailto(MailtoImmutable $mailto): MailtoBuilder
    {
        return new MailtoBuilder($mailto);
    }
}
