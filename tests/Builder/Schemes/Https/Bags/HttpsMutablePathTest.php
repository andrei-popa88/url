<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Https\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpsMutablePathTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions 10/?tag[]=networking&order=newest&tag[]=music#top';

    public function testGettersAndSetters()
    {
        $scheme = Scheme::https($this->validUrl);
        $https = Builder::https($scheme);

        $this->assertEquals([
            'forum',
            'questions 10'
        ],$https->getPathBag()->all());

        $this->assertEquals('/forum/questions 10/',$https->getPathBag()->raw());
        $this->assertEquals('/forum/questions+10/',$https->getPathBag()->encoded());
        $this->assertEquals('forum', $https->getPathBag()->get(0));
        $this->assertEquals(true,$https->getPathBag()->has(0));
        $this->assertEquals(false,$https->getPathBag()->has(2));

        $this->assertEquals([1 => 'questions 10'], $https->getPathBag()->forget('forum')->all());

        $this->assertEquals(['questions 10', 'inBetween'],
            $https->getPathBag()->putInBetween('inBetween', 'questions 10')->all());

        $this->assertEquals(['questions 10','beforeValue', 'inBetween'],
            $https->getPathBag()->putBefore('inBetween', 'beforeValue')->all());

        $this->assertEquals(['questions 10','beforeValue', 'afterValue', 'inBetween'],
            $https->getPathBag()->putAfter('beforeValue', 'afterValue')->all());

        $this->assertEquals(['prepend','questions 10','beforeValue', 'afterValue', 'inBetween'],
            $https->getPathBag()->prepend('prepend')->all());

        $this->assertEquals(['prepend','questions 10','beforeValue', 'afterValue', 'inBetween', 'append'],
            $https->getPathBag()->append('append')->all());

        $this->assertEquals(['beforeValue'],
            $https->getPathBag()->only('beforeValue'));

        $this->assertEquals([], $https->getPathBag()->forgetAll()->all());
    }
}