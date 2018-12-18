<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto;

use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoPathMutable;
use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoQueryMutable;
use Keppler\Url\Exceptions\InvalidComponentsException;
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
     * @param MailtoImmutable|null $mailto
     * @throws InvalidComponentsException
     */
    public function __construct(MailtoImmutable $mailto = null)
    {
        $this->queryBag = new MailtoQueryMutable();
        $this->pathBag = new MailtoPathMutable();

        if(null !== $mailto) {
            $this->populate($mailto);
        }
    }

    /**
     * @param MailtoImmutable $mailto
     * @throws InvalidComponentsException
     */
    private function populate(MailtoImmutable $mailto): void
    {
        $this->queryBag->setCc($mailto->getQueryBag()->getCc());
        $this->queryBag->setBcc($mailto->getQueryBag()->getBcc());
        $this->queryBag->setTo($mailto->getQueryBag()->getTo());
        $this->queryBag->setSubject($mailto->getQueryBag()->getSubject());
        $this->queryBag->setBody($mailto->getQueryBag()->getBody());
        $this->pathBag->setPath($mailto->getPathBag()->getPath());
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
     * @return MailtoPathMutable
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
        $url = self::SCHEME.':';

        if ($urlEncode) {
            $url .= $this->pathBag->encoded();
            $url .= $this->queryBag->encoded();
        } else {
            $url .= $this->pathBag->raw();
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
            'path' => $this->pathBag->all(),
            'query' => $this->queryBag->all(),
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
