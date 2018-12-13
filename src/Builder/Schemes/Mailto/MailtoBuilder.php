<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto;

use Keppler\Url\Builder\Schemes\Interfaces\SchemeInterface;
use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoQueryBag;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class MailtoBuilder
 *
 * @package Keppler\Url\Builder\Schemes\Mailto
 */
class MailtoBuilder implements SchemeInterface
{
    use Mutator;
    use Accessor;

    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME = 'mailto';

    /**
     * @var MailtoQueryBag
     */
    private $queryBag;

    /**
     * @var string | array
     */
    private $path = '';

    /**
     * MailtoBuilder constructor.
     *
     * @param MailtoImmutable $mailto
     */
    public function __construct(MailtoImmutable $mailto)
    {
        $this->queryBag = new MailtoQueryBag();
        $this->populate($mailto);
    }

    /**
     * @param MailtoImmutable $mailto
     */
    private function populate(MailtoImmutable $mailto): void
    {
        $this->path = $mailto->getPath();

        // The $mailto may not have any queries at all
        // Which is quite a common case thus the
        // Bag may not exist to begin with
        if (null !== $mailto->getQueryBag()) {
            $this->queryBag->setCc($mailto->getQueryBag()->getCc());
            $this->queryBag->setBcc($mailto->getQueryBag()->getBcc());
            $this->queryBag->setTo($mailto->getQueryBag()->getTo());
            $this->queryBag->setSubject($mailto->getQueryBag()->getSubject());
            $this->queryBag->setBody($mailto->getQueryBag()->getBody());
        } else {
            $this->queryBag = new MailtoQueryBag();
        }
    }

/////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return MailtoQueryBag|null
     */
    public function getQueryBag()
    {
        return $this->queryBag;
    }

    /**
     * @return array|string
     */
    public function getPath()
    {
        return $this->path;
    }

//////////////////////////
/// MUTATOR FUNCTIONS  ///
/////////////////////////

    /**
     * @param string $value
     * @return MailtoBuilder
     */
    public function appendToPath(string $value): self
    {
        if (!is_array($this->path)) {
            if ('' !== $this->path) {
                $this->path[] = $this->path;
            } else {
                $this->path = [];
            }
        }

        $this->append($this->path, $value);

        return $this;
    }

    /**
     * @param string $value
     * @return MailtoBuilder
     */
    public function prependToPath(string $value): self
    {
        if (!is_array($this->path)) {
            if ('' !== $this->path) {
                $this->path[] = $this->path;
            } else {
                $this->path = [];
            }
        }

        $this->prepend($this->path, $value);

        return $this;
    }

    /**
     * @return array|string
     */
    public function firstInPath()
    {
        if (is_array($this->path)) {
            return $this->firstIn($this->path);
        }

        return $this->path;
    }

    /**
     * @return array|string
     */
    public function lastInPath()
    {
        if (is_array($this->path)) {
            return $this->lastIn($this->path);
        }

        return $this->path;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasInPath(string $key): bool
    {
        if (is_array($this->path)) {
            return $this->hasKeyIn($this->path, $key);
        }

        return $key === $this->path;
    }

    /**
     * @param $keyOrValue
     * @return MailtoBuilder
     */
    public function forgetInPath($keyOrValue): self
    {
        if (is_array($this->path)) {
            $this->forgetKeyOrValue($this->path, $keyOrValue);

            return $this;
        }

        if ($keyOrValue === $this->path) {
            $this->path = '';
        }

        return $this;
    }

    /**
     * @return MailtoBuilder
     */
    public function forgetPath(): self
    {
        $this->path = '';

        return $this;
    }

/////////////////////////
/// SETTER FUNCTIONS  ///
/////////////////////////

    /**
     * @param $path array|string
     * @return $this
     * @throws InvalidComponentsException
     */
    public function setPath($path): self
    {
        if (is_array($path)) {
            if (count($path) !== count($path, COUNT_RECURSIVE)) {
                throw new InvalidComponentsException(sprintf('Unable to accept multidimensional arrays for $path component in %s',
                    __CLASS__));
            }
        }

        $this->path = $path;

        return $this;
    }

////////////////////////
/// OTHER FUNCTIONS  ///
////////////////////////

    /**
     * @param bool $urlEncode
     *
     * @return string
     */
    public function build(bool $urlEncode = false): string
    {
        // mailtoURL  =  "mailto:" [ to ] [ headers ]
        // to         =  #mailbox
        // headers    =  "?" header *( "&" header )
        // header     =  hname "=" hvalue
        // hname      =  *urlc
        // hvalue     =  *urlc

        $url = self::SCHEME.':';
        $commaEncoded = '%2C';

        // The path ca be either a single string value or an array of values
        if (is_array($this->path)) {
            foreach ($this->path as $email) {
                if ($urlEncode) {
                    $url .= $email.$commaEncoded;
                } else {
                    $url .= $email.',';
                }
            }
            $url = rtrim($url, ',');
        } else {
            $url .= $this->path;
        }

        if ($urlEncode) {
            $url .= $this->queryBag->encoded();
        } else {
            $url .= $this->queryBag->raw();
        }

        return $url;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

    /**
     * @return array
     */
    public function all(): array
    {
        return [
            'scheme' => self::SCHEME,
            'path' => $this->path,
            'query' => $this->getQueryBag()->all(),
        ];
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->build(false);
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return self::SCHEME;
    }
}
