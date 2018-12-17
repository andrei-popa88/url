<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Https\Bags;

use Keppler\Url\Scheme\Schemes\Https\Bags\HttpsImmutablePath;
use PHPUnit\Framework\TestCase;

class HttpsImmutablePathTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersEmptyUrl()
    {
        $https = new HttpsImmutablePath();

        $this->assertEquals('', $https->raw());
        $this->assertEquals([], $https->all());
        $this->assertEquals(null, $https->last());
        $this->assertEquals(null, $https->first());
        $this->assertEquals(false, $https->has(0));
    }

    public function testGettersNotEmptyUrl()
    {
        $parsed = parse_url($this->validUrl);
        $https = new HttpsImmutablePath($parsed['path']);

        $this->assertEquals('/forum/questions/', $https->raw());
        $this->assertEquals([
            'forum', 'questions'
        ], $https->all());
        $this->assertEquals('questions', $https->last());
        $this->assertEquals('forum', $https->first());
        $this->assertEquals(true, $https->has(0));

    }
}