<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Unit\Builder\Schemes\Mailto;

use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class MailtoBuilderTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    /** @test */
    public function shouldInitSuccessfullyTest()
    {
        $scheme = Scheme::mailto($this->validUrl);

        $builder = new MailtoBuilder($scheme);
        $this->assertInstanceOf(MailtoBuilder::class, $builder);
    }

    public function testGetters()
    {
        $scheme = Scheme::mailto($this->validUrl);
        $result = [
            'scheme' => 'mailto',
            'path' => [
                'path@email.com',
                'path2@email.com',
            ],
            'query' => [
                'to' => [
                    'email@example.com',
                    'email2@example.com',
                ],
                'cc' => [
                    'email3@example.com',
                    'email4@example.com',
                ],
                'bcc' => [
                    'email4@example.com',
                    'email5@example.com',
                ],
                'subject' => 'Hello',
                'body' => 'World',
            ],
        ];

        $builder = new MailtoBuilder($scheme);
        $this->assertEquals($result, $builder->all());
        $this->assertEquals('mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World',
            $builder->raw());
        $this->assertEquals('mailto:path@email.com%2Cpath2@email.com?to=email@example.com%2Cemail2@example.com&cc=email3@example.com%2Cemail4@example.com&bcc=email4@example.com%2Cemail5@example.com&subject=Hello&body=World',
            $builder->build(true));

        $this->assertEquals(MailtoBuilder::SCHEME, $builder->getScheme());
    }
}