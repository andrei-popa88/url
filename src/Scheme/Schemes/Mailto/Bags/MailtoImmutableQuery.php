<?php
declare(strict_types=1);

namespace Keppler\Url\Scheme\Schemes\Mailto\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Scheme\Interfaces\ImmutableBagInterface;
use Keppler\Url\Scheme\Schemes\AbstractImmutable;
use Keppler\Url\Traits\Accessor;

/**
 * Class MailtoImmutableQuery
 *
 * @package Keppler\Url\Schemes\MailtoImmutable\Bags
 */
class MailtoImmutableQuery extends AbstractImmutable implements ImmutableBagInterface
{
    use Accessor;

    /**
     * To recipients, can be more than one as
     * long they are separated by a comma
     *
     * @var array
     */
    private $to = [];

    /**
     * CarbonCopy recipients, can be more than one
     * as long as they are separated by a comma
     *
     * @var array
     */
    private $cc = [];

    /**
     * BlindCarbonCopy recipients, can be more than
     * one as long as they are separated by a comma
     *
     * @var array
     */
    private $bcc = [];

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * The raw query string
     *
     * @var string | null
     */
    private $raw = null;

    /**
     * This should be the ONLY entry point and it should accept ONLY the raw string
     *
     * MailtoImmutableQuery constructor.
     *
     * @param string $raw
     */
    public function __construct(string $raw = '')
    {
        // Leave the class with defaults if no valid raw string is provided
        if('' !== trim($raw)) {
            $this->raw = $raw;

            $result = [];
            parse_str($raw, $result);
            $this->buildFromParsed($result);
        }
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

///////////////////////
/// START PRIVATE  ///
//////////////////////

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

////////////////////
/// END PRIVATE  ///
///////////////////

    /**
     * @param int $key
     * @return string
     * @throws ComponentNotFoundException
     */
    public function getInTo(int $key): string
    {
        return $this->getIn($this->to, $key);
    }

    /**
     * @return array
     */
    public function firstInCc(): array
    {
        return $this->firstIn($this->cc);
    }

    /**
     * @return array
     */
    public function lastInCc(): array
    {
        return $this->lastIn($this->cc);
    }

    /**
     * @return array
     */
    public function firstInTo(): array
    {
        return $this->firstIn($this->to);
    }

    /**
     * @return array
     */
    public function lastInTo(): array
    {
        return $this->lastIn($this->cc);
    }

    /**
     * @return array
     */
    public function firstInBcc(): array
    {
        return $this->firstIn($this->bcc);
    }

    /**
     * @return array
     */
    public function lastInBcc(): array
    {
        return $this->lastIn($this->bcc);
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
     * @inheritDoc
     */
    public function all(): array
    {
        return [
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'subject' => $this->subject,
            'body' => $this->body,
        ];
    }

    /**
     * @inheritDoc
     */
    public function raw(): string
    {
        return null !== $this->raw ? $this->raw : '';
    }

    /**
     * This returns a class property instead of an array entry
     *
     * @param string $key
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function get(string $key)
    {
        if(!property_exists($this, $key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s', $key, __CLASS__));
        }

        return $this->$key;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return property_exists($this, $key);
    }
}
