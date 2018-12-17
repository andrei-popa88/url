<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Interfaces\Mutable\MutableBagInterface;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class MailtoQueryMutable
 *
 * @package Keppler\Url\Builder\Schemes\MailtoImmutable\Bags
 */
class MailtoQueryMutable implements MutableBagInterface
{
    use Accessor;
    use Mutator;

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

    /////////////////////////
    /// GETTER FUNCTIONS  ///
    ////////////////////////

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
     * @param int $key
     *
     * @return string
     * @throws ComponentNotFoundException
     */
    public function getInTo(int $key): string
    {
        return $this->getKeyIn($this->to, $key);
    }

    /**
     * @param int $key
     *
     * @return string
     * @throws ComponentNotFoundException
     */
    public function getInCc(int $key): string
    {
        return $this->getKeyIn($this->cc, $key);
    }

    /**
     * @param int $key
     *
     * @return string
     * @throws ComponentNotFoundException
     */
    public function getInBcc(int $key): string
    {
        return $this->getKeyIn($this->bcc, $key);
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /////////////////////////
    /// SETTER FUNCTIONS  ///
    ////////////////////////

    /**
     * @param array $to
     *
     * @return MailtoQueryMutable
     * @throws InvalidComponentsException
     */
    public function setTo(array $to): MailtoQueryMutable
    {
        if (count($to) !== count($to, COUNT_RECURSIVE)) {
            throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for $to component in %s',
                __CLASS__));
        }

        $this->to = array_values($to);

        return $this;
    }

    /**
     * @param string $body
     *
     * @return MailtoQueryMutable
     */
    public function setBody(string $body): MailtoQueryMutable
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param array $cc
     *
     * @return MailtoQueryMutable
     * @throws InvalidComponentsException
     */
    public function setCc(array $cc): MailtoQueryMutable
    {
        if (count($cc) !== count($cc, COUNT_RECURSIVE)) {
            throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for $cc component in %s',
                __CLASS__));
        }

        $this->cc = array_values($cc);

        return $this;
    }

    /**
     * @param array $bcc
     *
     * @return MailtoQueryMutable
     * @throws InvalidComponentsException
     */
    public function setBcc(array $bcc): MailtoQueryMutable
    {
        if (count($bcc) !== count($bcc, COUNT_RECURSIVE)) {
            throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for $bcc component in %s',
                __CLASS__));
        }

        $this->bcc = array_values($bcc);

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return MailtoQueryMutable
     */
    public function setSubject(string $subject): MailtoQueryMutable
    {
        $this->subject = $subject;

        return $this;
    }

    //////////////////////////
    /// MUTATOR FUNCTIONS  ///
    /////////////////////////

    /**
     * @return string|null
     */
    public function firstInCc(): ?string
    {
        return $this->firstInPath($this->cc);
    }

    /**
     * @return string|null
     */
    public function lastInCc(): ?string
    {
        return $this->lastInPath($this->cc);
    }

    /**
     * @return string|null
     */
    public function firstInTo(): ?string
    {
        return $this->firstInPath($this->to);
    }

    /**
     * @return string|null
     */
    public function lastInTo(): ?string
    {
        return $this->lastInPath($this->to);
    }

    /**
     * @return string|null
     */
    public function firstInBcc(): ?string
    {
        return $this->firstInPath($this->bcc);
    }

    /**
     * @return string|null
     */
    public function lastInBcc(): ?string
    {
        return $this->lastInPath($this->bcc);
    }

    /**
     * @param string $value
     *
     * @return MailtoQueryMutable
     */
    public function putInTo(string $value): self
    {
        $this->mutatorAppend($this->to, $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return MailtoQueryMutable
     */
    public function putInCc(string $value): self
    {
        $this->mutatorAppend($this->cc, $value);

        return $this;
    }

    /**
     * @param string $value
     *
     * @return MailtoQueryMutable
     */
    public function putInBcc(string $value): self
    {
        $this->mutatorAppend($this->cc, $value);

        return $this;
    }

    /**
     * @param $keyOrValue
     *
     * @return $this
     */
    public function forgetFromTo($keyOrValue): self
    {
        $this->mutatorForgetKeyOrValue($this->to, $keyOrValue);

        return $this;
    }

    /**
     * @param $keyOrValue
     *
     * @return $this
     */
    public function forgetFromCc($keyOrValue): self
    {
        $this->mutatorForgetKeyOrValue($this->cc, $keyOrValue);

        return $this;
    }

    /**
     * @param $keyOrValue
     *
     * @return $this
     */
    public function forgetFromBcc($keyOrValue): self
    {
        $this->mutatorForgetKeyOrValue($this->bcc, $keyOrValue);

        return $this;
    }

    /**
     * Sets to to an empty array
     */
    public function forgetTo(): self
    {
        $this->to = [];

        return $this;
    }

    /**
     * Sets cc to an empty array
     */
    public function forgetCc(): self
    {
        $this->cc = [];

        return $this;
    }

    /**
     * Sets bcc to an empty array
     */
    public function forgetBcc(): self
    {
        $this->bcc = [];

        return $this;
    }

    /**
     * Sets the subject to an empty string
     *
     * @return $this
     */
    public function forgetSubject(): self
    {
        $this->subject = '';

        return $this;
    }

    /**
     * @return MailtoQueryMutable
     */
    public function forgetBody(): self
    {
        $this->body = '';

        return $this;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function toHas(string $value): bool
    {
        return isset(array_flip($this->to)[$value]);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function ccHas(string $value): bool
    {
        return isset(array_flip($this->cc)[$value]);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function bccHas(string $value): bool
    {
        return isset(array_flip($this->bcc)[$value]);
    }


    /**
     * @return MailtoQueryMutable
     */
    public function forgetAll(): self
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];

        return $this;
    }

    /////////////////////////////////
    /// INTERFACE IMPLEMENTATION  ///
    ////////////////////////////////

    /**
     * @return array
     */
    public function all(): array
    {
        return [
            'to'      => $this->to,
            'cc'      => $this->cc,
            'bcc'     => $this->bcc,
            'subject' => $this->subject,
            'body'    => $this->body,
        ];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return property_exists($this, $key);
    }

    /**
     * @param $key
     *
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function get($key)
    {
        if ( ! $this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s',
                $key, __CLASS__));
        }

        return $this->$key;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return MutableBagInterface
     * @throws ComponentNotFoundException
     * @throws InvalidComponentsException
     */
    public function set($key, $value): MutableBagInterface
    {
        if ( ! $this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s',
                $key, __CLASS__));
        }

        if (is_array($this->$key)) {
            if (count($value) !== count($value, COUNT_RECURSIVE)) {
                throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for %s component in %s',
                    $key, __CLASS__));
            }

            $this->$key = array_values($value);

            return $this;
        }

        $this->$key = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function encoded(): string
    {
        return $this->buildQuery(true);
    }

    /**
     * @inheritDoc
     */
    public function raw(): string
    {
        return $this->buildQuery(false);
    }

    ////////////////////////
    /// OTHER FUNCTIONS ///
    ///////////////////////

    /**
     * @param bool $urlEncode
     *
     * @return string
     */
    private function buildQuery(bool $urlEncode = false): string
    {
        $query = '?';
        $encodedComma = '%2C'; // only valid encoded delimiter - encoded comma
        $trim = $encodedComma.',';

        if ( ! empty($this->getTo())) {
            $query .= '&to=';
            foreach ($this->to as $value) {
                if ($urlEncode) {
                    $query .= $value.$encodedComma;
                } else {
                    $query .= $value.',';
                }
            }

            $query = rtrim($query, $trim);
        }

        if ( ! empty($this->cc)) {
            $query .= '&cc=';
            foreach ($this->cc as $value) {
                if ($urlEncode) {
                    $query .= $value.$encodedComma;
                } else {
                    $query .= $value.',';
                }
            }

            $query = rtrim($query, $trim);
        }

        if ( ! empty($this->bcc)) {
            $query .= '&bcc=';
            foreach ($this->bcc as $value) {
                if ($urlEncode) {
                    $query .= $value.$encodedComma;
                } else {
                    $query .= $value.',';
                }
            }

            $query = rtrim($query, $trim);
        }

        if ('' !== trim($this->subject)) {
            if ($urlEncode) {
                $query .= '&subject='.urlencode($this->subject);

            } else {
                $query .= '&subject='.$this->subject;
            }
        }

        if ('' !== trim($this->body)) {
            if ($urlEncode) {
                $query .= '&body='.urlencode($this->body);

            } else {
                $query .= '&body='.$this->body;
            }
        }

        if ('?' !== $query) {
            $query = ltrim($query, '?&');
            $query = '?'.$query;

            return $query;
        }

        return '';
    }
}
