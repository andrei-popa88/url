<?php

namespace Keppler\Url\Test;

use Keppler\Url\Url;
use PHPUnit\Framework\TestCase;

class QueryBagTest extends TestCase
{
    public function test_empty_query()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/';
        $urlClass = Url::from($url);
        $this->assertEquals(true, empty($urlClass->parser->getQueryBag()->all()));
    }

    public function test_not_empty_query()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals([
                'tag' => 'networking',
                'order' => 'newest',
                'date' => '2015-11-12'
        ], $urlClass->parser->getQueryBag()->all());
    }

    public function test_get_first_query_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals('networking', $urlClass->parser->getQueryBag()->getFirstQueryParam());
    }

    public function test_get_last_query_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals('2015-11-12', $urlClass->parser->getQueryBag()->getLastQueryParam());
    }

    public function test_has_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals(true, $urlClass->parser->getQueryBag()->has('tag'));
    }

    public function test_does_not_have_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals(false, $urlClass->parser->getQueryBag()->has('invalid_param'));
    }

    public function test_has_param_by_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals('newest', $urlClass->parser->getQueryBag()->get('order'));
    }

    public function test_does_not_has_param_by_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $urlClass = Url::from($url);
        $this->assertEquals(null, $urlClass->parser->getQueryBag()->get('invalid_param'));
    }
}