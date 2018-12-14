<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto;

use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoPathMutable;
use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoQueryMutable;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Interfaces\Immutable\ImmutableSchemeInterface;
use Keppler\Url\Interfaces\Mutable\MutableSchemeInterface;
use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;
use Keppler\Url\Traits\Accessor;
use Keppler\Url\Traits\Mutator;

/**
 * Class MailtoBuilder
 *
 * @package Keppler\Url\Builder\Schemes\MailtoImmutable
 */
class MailtoBuilder implements MutableSchemeInterface
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
     * @var MailtoQueryMutable
     */
    private $queryBag;

    /**
     * @var MailtoPathMutable
     */
    private $pathBag;

    /**
     * MailtoBuilder constructor.
     *
     * @param ImmutableSchemeInterface $mailto
     *
     * @throws InvalidComponentsException
     */
    public function __construct(ImmutableSchemeInterface $mailto)
    {
        $this->queryBag = new MailtoQueryMutable();
        $this->pathBag = new MailtoPathMutable();
        $this->populate($mailto);
    }

    /**
     * @param MailtoImmutable $mailto
     * @throws InvalidComponentsException
     */
    private function populate(MailtoImmutable $mailto): void
    {
        $this->pathBag = $mailto->getPath();

        $this->queryBag->setCc($mailto->getQueryBag()->getCc());
        $this->queryBag->setBcc($mailto->getQueryBag()->getBcc());
        $this->queryBag->setTo($mailto->getQueryBag()->getTo());
        $this->queryBag->setSubject($mailto->getQueryBag()->getSubject());
        $this->queryBag->setBody($mailto->getQueryBag()->getBody());

    }

/////////////////////////
/// GETTER FUNCTIONS  ///
////////////////////////

    /**
     * @return MailtoQueryMutable|null
     */
    public function getQueryBag()
    {
        return $this->queryBag;
    }

    /**
     * @return array|string
     */
    public function getPathBag()
    {
        return $this->pathBag;
    }

/////////////////////////////////
/// INTERFACE IMPLEMENTATION  ///
/////////////////////////////////

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
        if (is_array($this->pathBag)) {
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
