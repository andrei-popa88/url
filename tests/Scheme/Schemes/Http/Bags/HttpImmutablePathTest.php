<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Http\Bags;

use Keppler\Url\Scheme\Schemes\Http\Bags\HttpImmutablePath;
use PHPUnit\Framework\TestCase;

class HttpImmutablePathTest extends TestCase
{
    private $validUrl = 'http://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersEmptyUrl()
    {
        $https = new HttpImmutablePath();

        $this->assertEquals('', $https->raw());
        $this->assertEquals([], $https->all());
        $this->assertEquals(null, $https->last());
        $this->assertEquals(null, $https->first());
        $this->assertEquals(false, $https->has(0));
    }

    public function testGettersNotEmptyUrl()
    {
        $parsed = parse_url($this->validUrl);
        $https = new HttpImmutablePath($parsed['path']);

        $this->assertEquals('/forum/questions/', $https->raw());
        $this->assertEquals([
            'forum', 'questions'
        ], $https->all());
        $this->assertEquals('questions', $https->last());
        $this->assertEquals('forum', $https->first());
        $this->assertEquals(true, $https->has(0));

    }
}