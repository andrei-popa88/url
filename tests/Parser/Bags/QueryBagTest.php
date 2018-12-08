<?php

namespace Keppler\Url\Test;

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
        $this->assertEquals('networking', $parser->query->first());
    }

    public function test_get_last_query_param()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';
        $parser = Parser::from($url);
        $this->assertEquals('2015-11-12', $parser->query->last());
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
        $this->assertEquals('tag=networking&order=newest', $parser->query->original());
    }

    public function test_get_original_path_null()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert#top';
        $parser = Parser::from($url);
        $this->assertEquals(null, $parser->query->original());
    }
}