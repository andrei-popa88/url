<?php

namespace Keppler\Url\Test\Parser\Bags;

use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class PathBagTest extends TestCase
{
    public function test_should_not_have_components()
    {
        $url = 'https://john.doe@www.example.com:123?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals(true, empty($parser->path->all()));
    }

    public function test_should_have_components()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals(['forum', 'questions'], $parser->path->all());
    }

    public function test_get_first_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals('forum', $parser->path->first());
    }

    public function test_get_last_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);

        $this->assertEquals('assert', $parser->path->last());
    }

    public function test_has_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);

        $this->assertEquals(true, $parser->path->has(1));
    }

    public function test_does_not_have_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals(false, $parser->path->has(11));
    }

    public function test_has_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals('forum', $parser->path->get(0));
    }

    public function test_does_not_have_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals(null, $parser->path->get(11));
    }

    public function test_get_original_path_not_null()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals('/forum/questions/phpunit/assert', $parser->path->raw());
    }

    public function test_get_original_path_null()
    {
        $url = 'http://john.doe@www.example.com:123?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $this->assertEquals(null, $parser->path->raw());
    }
}