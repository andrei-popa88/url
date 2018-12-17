<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Http\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpMutableQueryTest extends TestCase
{
    private $validUrl = 'http://john:password@www.example.com:123/forum/questions 10/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersAndSetters()
    {
        $scheme = Scheme::http($this->validUrl);
        $http = Builder::http($scheme);

        $this->assertEquals('?tag%5B0%5D=networking&tag%5B1%5D=music&order=newest', $http->getQueryBag()->encoded());
        $this->assertEquals('?tag[0]=networking&tag[1]=music&order=newest', $http->getQueryBag()->raw());
        $this->assertEquals([
            'tag' => [
                'networking',
                'music',
            ],
            'order' => 'newest',
        ], $http->getQueryBag()->all());

        $this->assertEquals(['order' => 'newest'], $http->getQueryBag()->only('order'));
        $this->assertEquals(true, $http->getQueryBag()->has('tag'));
        $this->assertEquals(false, $http->getQueryBag()->has('invalid'));
        $this->assertEquals([
            'networking',
            'music',
        ], $http->getQueryBag()->get('tag'));

        $this->assertEquals(
            [
                'tag' =>
                    [
                        'networking',
                        'music',
                    ],
            ], $http->getQueryBag()->first());
        $this->assertEquals(['order' => 'newest'], $http->getQueryBag()->last());
    }
}