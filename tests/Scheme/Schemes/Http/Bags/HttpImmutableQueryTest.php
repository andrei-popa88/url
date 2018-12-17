<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Https\Bags;

use Keppler\Url\Scheme\Schemes\Http\Bags\HttpImmutableQuery;
use PHPUnit\Framework\TestCase;

class HttpImmutableQueryTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersEmptyUrl()
    {
        $https = new HttpImmutableQuery();

        $this->assertEquals('', $https->raw());
        $this->assertEquals([], $https->all());
        $this->assertEquals(null, $https->last());
        $this->assertEquals(null, $https->first());
        $this->assertEquals(false, $https->has(0));
    }

    public function testGettersNotEmptyUrl()
    {
        $parsed = parse_url($this->validUrl);
        $https = new HttpImmutableQuery($parsed['query']);

        $this->assertEquals('tag[]=networking&order=newest&tag[]=music', $https->raw());
        $this->assertEquals([
            'tag' => ['networking', 'music'],
            'order' => 'newest',
        ], $https->all());
        $this->assertEquals('newest', $https->last());
        $this->assertEquals(['tag' => ['networking', 'music']], $https->first());
        $this->assertEquals(true, $https->has('tag'));
    }

}