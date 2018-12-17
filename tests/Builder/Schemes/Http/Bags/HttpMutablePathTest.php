<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Http\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpMutablePathTest extends TestCase
{
    private $validUrl = 'http://john:password@www.example.com:123/forum/questions 10/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersAndSetters()
    {
        $scheme = Scheme::http($this->validUrl);
        $http = Builder::http($scheme);

        $this->assertEquals([
            'forum',
            'questions 10'
        ],$http->getPathBag()->all());

        $this->assertEquals('forum', $http->getPathBag()->get(0));

        $this->assertEquals('/forum/questions 10/',$http->getPathBag()->raw());
        $this->assertEquals('/forum/questions+10/',$http->getPathBag()->encoded());
        $this->assertEquals(true,$http->getPathBag()->has(0));
        $this->assertEquals(false,$http->getPathBag()->has(2));

        $this->assertEquals([1 => 'questions 10'], $http->getPathBag()->forget('forum')->all());

        $this->assertEquals(['questions 10', 'inBetween'],
            $http->getPathBag()->putInBetween('inBetween', 'questions 10')->all());

        $this->assertEquals(['questions 10','beforeValue', 'inBetween'],
            $http->getPathBag()->putBefore('inBetween', 'beforeValue')->all());

        $this->assertEquals(['questions 10','beforeValue', 'afterValue', 'inBetween'],
            $http->getPathBag()->putAfter('beforeValue', 'afterValue')->all());

        $this->assertEquals(['prepend','questions 10','beforeValue', 'afterValue', 'inBetween'],
            $http->getPathBag()->prepend('prepend')->all());

        $this->assertEquals(['prepend','questions 10','beforeValue', 'afterValue', 'inBetween', 'append'],
            $http->getPathBag()->append('append')->all());

        $this->assertEquals(['beforeValue'],
            $http->getPathBag()->only('beforeValue'));

        $this->assertEquals([], $http->getPathBag()->forgetAll()->all());
    }
}