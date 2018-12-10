<?php

namespace Keppler\Url\Test\Parser\Bags;

use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class QueryBagTest extends TestCase
{
    public function test_empty_query()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/';
        $parser = Parser::from($url);
        $this->assertEquals(true, empty($parser->query->all()));
    }

    public function test_not_empty_query()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals([
                'tag' => 'networking',
                'order' => 'newest',
                'date' => '2015-11-12'
        ], $parser->query->all());
    }

    public function test_get_first_query_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals(['tag' => 'networking'], $parser->query->first());
    }

    public function test_get_last_query_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals(['date' => '2015-11-12'], $parser->query->last());
    }

    public function test_has_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals(true, $parser->query->has('tag'));
    }

    public function test_does_not_have_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals(false, $parser->query->has('invalid_param'));
    }

    public function test_has_param_by_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals('newest', $parser->query->get('order'));
    }

    public function test_does_not_has_param_by_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals(null, $parser->query->get('invalid_param'));
    }

    public function test_get_original_path_not_null()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals('tag=networking&order=newest', $parser->query->raw());
    }

    public function test_get_original_path_null()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert#top';
        $parser = Parser::from($url);
        $this->assertEquals('', $parser->query->raw());
    }

    public function test_get_firstin_not_multidimensional()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[]=baz&arr[test]=value';
        $parser = Parser::from($url);
        $this->assertEquals([0 => 'foo bar'], $parser->query->firstIn('arr'));
    }

    public function test_get_firstin_multidimensional()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[]=baz&arr[test]=value';
        $parser = Parser::from($url);
        $this->assertEquals(['test' => 'value'], $parser->query->lastIn('arr'));
    }

    public function test_get_firstin_one_dimensional()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[]=baz&arr[test]=value';
        $parser = Parser::from($url);
        $this->assertEquals('value', $parser->query->lastIn('first'));
    }


    public function test_last_firstin_one_dimensional()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[]=baz&arr[test]=value&new_index=new_value';
        $parser = Parser::from($url);
        $this->assertEquals('new_value', $parser->query->lastIn('new_index'));
    }
}