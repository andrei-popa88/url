<?php
declare(strict_types=1);

namespace Keppler\Url\Schemes\Mailto\Bags;

use Keppler\Url\Schemes\Interfaces\ImmutableQueryBagInterface;

/**
 * Class MailtoImmutableQueryBag
 *
 * @package Keppler\Url\Schemes\MailtoImmutable\Bags
 */
final class MailtoImmutableQueryBag implements ImmutableQueryBagInterface
{
    /**
     * TO recipients
     * Can have multiple to recipients as long as
     * they are separated by a comma
     *
     * @var array
     */
    private $to = [];

    /**
     * CarbonCopy recipients, can be more than one as long as they
     * are separated by a comma. The getter should take
     * care of any encoding the setter WILL always decode it
     *
     * @var array
     */
    private $cc = [];

    /**
     * BlindCarbonCopy recipients, can be more than one as long as they
     * are separated by an encoded comma. The getter should take
     * care of any encoding the setter WILL always decode it
     *
     * @var array
     */
    private $bcc = [];

    /**
     * The setter WILL always decode it before assigning it
     *
     * @var string
     */
    private $subject = '';

    /**
     * The setter WILL always decode it before assigning it
     *
     * @var string
     */
    private $body = '';

    /**
     * The raw query string
     *
     * @var string
     */
    private $raw = '';

    /**
     * This should be the ONLY entry point and it should accept ONLY
     * the raw string, it's the job of this class to set from it
     *
     * MailtoImmutableQueryBag constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw)
    {
        $this->raw = $raw;

        $result = [];
        parse_str($raw, $result);
        $this->buildFromParsed($result);
    }

    /**
     * @param array $parsed
     */
    private function buildFromParsed(array $parsed): void
    {
        // NOTE!
        // No validation of emails will occur, it's the job of the caller to do that

        // Check ONLY for accepted values in the rfc
        // There's no point in looking for something else

        // Some functions will check if there are multiple email addresses by

        // Try to set $to
        if (isset($parsed['to']) && ! empty(trim($parsed['to']))) {
            $this->setTo($parsed['to']);
        }

        // Try to set $cc
        if (isset($parsed['cc']) && ! empty(trim($parsed['cc']))) {
            $this->setCc($parsed['cc']);
        }

        // Try to set $bcc
        if (isset($parsed['bcc']) && ! empty(trim($parsed['bcc']))) {
            $this->setBcc($parsed['bcc']);
        }

        // Try to set $subject
        if (isset($parsed['subject']) && ! empty(trim($parsed['subject']))
        ) {
            $this->subject = $parsed['subject'];
        }

        // Try to set $body
        if (isset($parsed['body']) && ! empty(trim($parsed['body']))) {
            $this->body = $parsed['body'];
        }
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return array
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $bcc
     */
    private function setBcc(string $bcc): void
    {
        if (false !== strpos($bcc, ',')) {
            $this->bcc = explode(',', $bcc);
        } else {
            $this->bcc[] = $bcc;
        }
    }

    /**
     * @param string $cc
     */
    private function setCc(string $cc): void
    {
        if (false !== strpos($cc, ',')) {
            $this->cc = explode(',', $cc);
        } else {
            $this->cc[] = $cc;
        }
    }

    /**
     * @param string $to
     */
    private function setTo(string $to): void
    {
        if (false !== strpos($to, ',')) {
            $this->to = explode(',', $to);
        } else {
            $this->to[] = $to;
        }
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return [
            'to' => $this->to,
        ];
    }
}
