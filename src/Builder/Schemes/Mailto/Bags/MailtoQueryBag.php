<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Builder\Schemes\Interfaces\BagInterface;
use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class MailtoQueryBag
 *
 * @package Keppler\Url\Builder\Schemes\Mailto\Bags
 */
class MailtoQueryBag implements BagInterface
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
     * @return string
     * @throws ComponentNotFoundException
     */
    public function getInTo(int $key): string
    {
        return $this->getIn($this->to, $key);
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
     * @return MailtoQueryBag
     * @throws InvalidComponentsException
     */
    public function setTo(array $to): MailtoQueryBag
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
     * @return MailtoQueryBag
     */
    public function setBody(string $body): MailtoQueryBag
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param array $cc
     * @return MailtoQueryBag
     * @throws InvalidComponentsException
     */
    public function setCc(array $cc): MailtoQueryBag
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
     * @return MailtoQueryBag
     * @throws InvalidComponentsException
     */
    public function setBcc(array $bcc): MailtoQueryBag
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
     * @return MailtoQueryBag
     */
    public function setSubject(string $subject): MailtoQueryBag
    {
        $this->subject = $subject;

        return $this;
    }

//////////////////////////
/// MUTATOR FUNCTIONS  ///
/////////////////////////

    /**
     * @return string
     */
    public function firstInCc(): string
    {
        return $this->firstIn($this->cc);
    }

    /**
     * @return string
     */
    public function lastInCc(): string
    {
        return $this->lastIn($this->cc);
    }

    /**
     * @return string
     */
    public function firstInTo(): string
    {
        return $this->firstIn($this->to);
    }

    /**
     * @return string
     */
    public function lastInTo(): string
    {
        return $this->lastIn($this->cc);
    }

    /**
     * @return string
     */
    public function firstInBcc(): string
    {
        return $this->firstIn($this->bcc);
    }

    /**
     * @return string
     */
    public function lastInBcc(): string
    {
        return $this->lastIn($this->bcc);
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function appendToTo(string $value): self
    {
        $this->append($this->to, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function appendToCc(string $value): self
    {
        $this->append($this->cc, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function appendToBcc(string $value): self
    {
        $this->append($this->cc, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function prependToTo(string $value): self
    {
        $this->prepend($this->to, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function prependToCC(string $value): self
    {
        $this->prepend($this->cc, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoQueryBag
     */
    public function prependToBcc(string $value): self
    {
        $this->prepend($this->bcc, $value);

        return $this;
    }

    /**
     * @param $keyOrValue
     * @return $this
     */
    public function forgetFromTo($keyOrValue): self
    {
        $this->forgetKeyOrValue($this->to, $keyOrValue);

        return $this;
    }

    /**
     * @param $keyOrValue
     * @return $this
     */
    public function forgetFromCc($keyOrValue): self
    {
        $this->forgetKeyOrValue($this->cc, $keyOrValue);

        return $this;
    }

    /**
     * @param $keyOrValue
     * @return $this
     */
    public function forgetFromBcc($keyOrValue): self
    {
        $this->forgetKeyOrValue($this->bcc, $keyOrValue);

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
     * @return MailtoQueryBag
     */
    public function forgetBody(): self
    {
        $this->body = '';

        return $this;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function toHas(string $value): bool
    {
        return isset(array_flip($this->to)[$value]);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function ccHas(string $value): bool
    {
        return isset(array_flip($this->cc)[$value]);
    }

    /**
     * @param string $value
     * @return bool
     */
    public function bccHas(string $value): bool
    {
        return isset(array_flip($this->bcc)[$value]);
    }

////////////////////////////////
/// INTEFACE IMPLEMENTATION  ///
///////////////////////////////

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
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return property_exists($this, $key);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s', $key, __CLASS__));
        }

        return $this->$key;
    }

    /**
     * @param $key
     * @param $value
     * @return BagInterface
     * @throws ComponentNotFoundException
     * @throws InvalidComponentsException
     */
    public function set($key, $value): BagInterface
    {
        if (!$this->has($key)) {
            throw new ComponentNotFoundException(sprintf('Component %s does not exist in %s', $key, __CLASS__));
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
     * @return string
     */
    private function buildQuery(bool $urlEncode = false): string
    {
        $query = '?';
        $encodedComma = '%2C'; // only valid encoded delimiter - encoded comma
        $trim = $encodedComma.',';

        if (!empty($this->getTo())) {
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

        if (!empty($this->cc)) {
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

        if (!empty($this->bcc)) {
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
