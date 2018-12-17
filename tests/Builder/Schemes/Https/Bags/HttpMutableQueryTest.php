<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Https\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpMutableQueryTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions 10/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersAndSetters()
    {
        $scheme = Scheme::https($this->validUrl);
        $https = Builder::https($scheme);

        $this->assertEquals('?tag%5B0%5D=networking&tag%5B1%5D=music&order=newest', $https->getQueryBag()->encoded());
        $this->assertEquals('?tag[0]=networking&tag[1]=music&order=newest', $https->getQueryBag()->raw());
        $this->assertEquals([
            'tag' => [
                'networking',
                'music',
            ],
            'order' => 'newest',
        ], $https->getQueryBag()->all());

        $this->assertEquals(['order' => 'newest'], $https->getQueryBag()->only('order'));
        $this->assertEquals(true, $https->getQueryBag()->has('tag'));
        $this->assertEquals(false, $https->getQueryBag()->has('invalid'));
        $this->assertEquals([
            'networking',
            'music',
        ], $https->getQueryBag()->get('tag'));

        $this->assertEquals(
            [
                'tag' =>
                    [
                        'networking',
                        'music',
                    ],
            ], $https->getQueryBag()->first());
        $this->assertEquals(['order' => 'newest'], $https->getQueryBag()->last());

    }
}