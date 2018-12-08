<?php

namespace Keppler\Url\Test;

use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class PathBagTest extends TestCase
{
    public function test_should_not_have_components()
    {
        $url = 'https://john.doe@www.example.com:123?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals(true, empty($parser->getPathBag()->all()));
    }

    public function test_should_have_components()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals(['forum', 'questions'], $parser->getPathBag()->all());
    }

    public function test_get_first_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals('forum', $parser->getPathBag()->getFirstPathParam());
    }

    public function test_get_last_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);

        $this->assertEquals('assert', $parser->getPathBag()->getLastPathParam());
    }

    public function test_has_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);

        $this->assertEquals(true, $parser->getPathBag()->has(1));
    }

    public function test_does_not_have_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals(false, $parser->getPathBag()->has(11));
    }

    public function test_has_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals('forum', $parser->getPathBag()->get(0));
    }

    public function test_does_not_have_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $this->assertEquals(null, $parser->getPathBag()->get(11));
    }
}