<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Mailto\Bags;

use Keppler\Url\Scheme\Schemes\Mailto\Bags\MailtoImmutablePath;
use PHPUnit\Framework\TestCase;

class MailtoImmutablePathTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'
        .
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    public function testGettersEmptyUrl()
    {
        $mailto = new MailtoImmutablePath();

        $this->assertEquals('', $mailto->raw());
        $this->assertEquals([], $mailto->all());
        $this->assertEquals(null, $mailto->first());
        $this->assertEquals(null, $mailto->last());
        $this->assertEquals([], $mailto->getPath());
        $this->assertEquals(false, $mailto->hasInPath('invalid value'));
    }

    public function testGettersNotEmptyUrl()
    {
        $parsedUrl = parse_url($this->validUrl);

        $mailto = new MailtoImmutablePath($parsedUrl['path']);

        $this->assertEquals('path@email.com,path2@email.com',
            $mailto->raw());

        $this->assertEquals([
            'path@email.com',
            'path2@email.com'
        ], $mailto->all());

        $this->assertEquals('path@email.com', $mailto->first());
        $this->assertEquals('path2@email.com', $mailto->last());
        $this->assertEquals([
            'path@email.com',
            'path2@email.com'
        ], $mailto->getPath());
        $this->assertEquals(false, $mailto->hasInPath('invalid value'));
    }
}