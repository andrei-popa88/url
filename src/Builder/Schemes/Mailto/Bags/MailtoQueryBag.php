<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Builder\Schemes\Interfaces\BagInterface;
use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Traits\Accessor;

/**
 * Class MailtoQueryBag
 *
 * @package Keppler\Url\Builder\Schemes\Mailto\Bags
 */
class MailtoQueryBag implements BagInterface
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
     * @var string
     */
    private $raw = '';

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param array $to
     *
     * @return MailtoQueryBag
     */
    public function setTo(array $to): MailtoQueryBag
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return array
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @param array $cc
     *
     * @return MailtoQueryBag
     */
    public function setCc(array $cc): MailtoQueryBag
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     *
     * @return MailtoQueryBag
     */
    public function setBcc(array $bcc): MailtoQueryBag
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return MailtoQueryBag
     */
    public function setSubject(string $subject): MailtoQueryBag
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return MailtoQueryBag
     */
    public function setBody(string $body): MailtoQueryBag
    {
        $this->body = $body;

        return $this;
    }

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
       // TODO Implement this
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return property_exists($this, $key);
    }

    /**
     * @inheritDoc
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
    public function set($key, $value): BagInterface
    {
        if(!$this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s', $key, __CLASS__));
        }

        $this->$key = $value;

        return $this;
    }


}
