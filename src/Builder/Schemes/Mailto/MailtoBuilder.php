<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Schemes\Mailto;

use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoQueryBag;
use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;

/**
 * Class MailtoBuilder
 *
 * @package Keppler\Url\Builder\Schemes\Mailto
 */
final class MailtoBuilder
{
    /**
     * The default scheme for this class
     *
     * @var string
     */
    const SCHEME_MAILTO = 'mailto';

    /**
     * @var null
     */
    private $queryBag = null;

    /**
     * @var string | array
     */
    private $path = null;

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
        if(null !== $mailto->getQueryBag()) {
            $this->queryBag->setCc($mailto->getQueryBag()->getCc());
            $this->queryBag->setBcc($mailto->getQueryBag()->getBcc());
            $this->queryBag->setTo($mailto->getQueryBag()->getTo());
            $this->queryBag->setSubject($mailto->getQueryBag()->getSubject());
            $this->queryBag->setBody($mailto->getQueryBag()->getBody());
        }
    }

    /**
     * @return MailtoQueryBag|null
     */
    public function getQueryBag() {
        return $this->queryBag;
    }

    /**
     * @return array|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array|string $path
     *
     * @return MailtoBuilder
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

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

        $url = self::SCHEME_MAILTO . ':';
        $commaEncoded = '%2C';

        // The path ca be either a single string value or an array of values
        if(is_array($this->path)) {
            foreach($this->path as $email) {
                if($urlEncode) {
                    $url .= $email . $commaEncoded;
                }else{
                    $url .= $email . ',';
                }
            }
            $url = rtrim($url, ',');
        }else {
            $url .= $this->path;
        }

        $query = '?';

        if(!empty($this->queryBag->getTo())) {
            if($urlEncode) {
                $query .= '&to=' . rtrim(implode($commaEncoded, $this->queryBag->getTo()), $commaEncoded);

            } else {
                $query .= '&to=' . rtrim(implode(',', $this->queryBag->getTo()), ',');
            }
        }

        if(!empty($this->queryBag->getCc())) {
            if($urlEncode) {
                $query .= '&cc=' . rtrim(implode($commaEncoded, $this->queryBag->getCc()), $commaEncoded);

            } else {
                $query .= '&cc=' . rtrim(implode(',', $this->queryBag->getCc()), ',');
            }
        }

        if(!empty($this->queryBag->getBcc())) {
            if($urlEncode) {
                $query .= '&bcc=' . rtrim(implode($commaEncoded, $this->queryBag->getBcc()), $commaEncoded);

            } else {
                $query .= '&bcc=' . rtrim(implode(',', $this->queryBag->getBcc()), ',');
            }
        }

        if('' !== trim($this->queryBag->getSubject())) {
            if($urlEncode) {
                $query .= '&subject=' . urlencode($this->queryBag->getSubject());

            } else {
                $query .= '&subject=' . $this->queryBag->getSubject();
            }
        }

        if('' !== trim($this->queryBag->getBody())) {
            if($urlEncode) {
                $query .= '&body=' . urlencode($this->queryBag->getBody());

            } else {
                $query .= '&body=' . $this->queryBag->getBody();
            }
        }

        if('?' !== $query) {
            $query = ltrim($query, '?&');
            $query = '?' . $query;

            return $url . $query;
        }

        return $url;
    }

}
