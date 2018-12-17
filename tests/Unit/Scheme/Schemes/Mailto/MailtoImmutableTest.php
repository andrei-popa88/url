<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Unit\Scheme\Schemes\Mailto;

use Keppler\Url\Scheme\Schemes\Mailto\MailtoImmutable;
use PHPUnit\Framework\TestCase;

class MailtoImmutableTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    public function testShouldInitSuccessfully()
    {
        $this->assertInstanceOf(MailtoImmutable::class,
            new MailtoImmutable($this->validUrl));
    }

    public function testGetters()
    {
        $result = [
            'scheme' => 'mailto',
            'path'   => [
                'path@email.com',
                'path2@email.com',
            ],
            'query'  => [
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
            ],
        ];
        $mailto = new MailtoImmutable($this->validUrl);

        $this->assertEquals($result, $mailto->all());

        $this->assertEquals(MailtoImmutable::SCHEME, $mailto->getScheme());

        $this->assertEquals($this->validUrl, $mailto->raw());
    }

    public function testShouldThrowInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new MailtoImmutable('invalid:path@example.com');
    }
}