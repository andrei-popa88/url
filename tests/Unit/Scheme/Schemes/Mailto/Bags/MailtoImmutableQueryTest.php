<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Unit\Scheme\Schemes\Mailto\Bags;

use Keppler\Url\Scheme\Schemes\Mailto\Bags\MailtoImmutableQuery;
use PHPUnit\Framework\TestCase;

class MailtoImmutableQueryTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    public function testGettersEmptyUrl()
    {
        $mailto = new MailtoImmutableQuery();

        $this->assertEquals('', $mailto->raw());
        $this->assertEquals([
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'subject' => '',
            'body' => '',
        ], $mailto->all());
        $this->assertEquals([], $mailto->getBcc());
        $this->assertEquals('', $mailto->getBody());
        $this->assertEquals('', $mailto->getSubject());
        $this->assertEquals([], $mailto->getCc());
        $this->assertEquals([], $mailto->getTo());

        $this->assertEquals(false, $mailto->hasInBcc('no value'));
        $this->assertEquals(false, $mailto->hasInCc('no value'));
        $this->assertEquals(false, $mailto->hasInTo('no value'));

        $this->assertEquals(null, $mailto->lastInCc());
        $this->assertEquals(null, $mailto->lastInBcc());
        $this->assertEquals(null, $mailto->lastInTo());

        $this->assertEquals(null, $mailto->firstInBcc());
        $this->assertEquals(null, $mailto->firstInCc());
        $this->assertEquals(null, $mailto->firstInTo());

    }

    public function testGettersNotEmptyUrl()
    {
        $parsedUrl = parse_url($this->validUrl);

        $mailto = new MailtoImmutableQuery($parsedUrl['query']);

        $this->assertEquals('to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World',
            $mailto->raw());

        $this->assertEquals([
            'to'      => [
                'email@example.com',
                'email2@example.com',
            ],
            'cc'      => [
                'email3@example.com',
                'email4@example.com',
            ],
            'bcc'     => [
                'email4@example.com',
                'email5@example.com',
            ],
            'subject' => 'Hello',
            'body'    => 'World',
        ], $mailto->all());

        $this->assertEquals([
            'email@example.com',
            'email2@example.com',
        ], $mailto->getTo());

        $this->assertEquals([
            'email3@example.com',
            'email4@example.com',
        ], $mailto->getCc());

        $this->assertEquals([
            'email4@example.com',
            'email5@example.com',
        ], $mailto->getBcc());

        $this->assertEquals('Hello', $mailto->getSubject());
        $this->assertEquals('World', $mailto->getBody());

        $this->assertEquals(true, $mailto->hasInBcc('email4@example.com'));
        $this->assertEquals(true, $mailto->hasInCc('email3@example.com'));
        $this->assertEquals(true, $mailto->hasInTo('email@example.com'));

        $this->assertEquals('email4@example.com', $mailto->lastInCc());
        $this->assertEquals('email5@example.com', $mailto->lastInBcc());
        $this->assertEquals('email2@example.com', $mailto->lastInTo());

        $this->assertEquals('email4@example.com', $mailto->firstInBcc());
        $this->assertEquals('email3@example.com', $mailto->firstInCc());
        $this->assertEquals('email@example.com', $mailto->firstInTo());
    }
}