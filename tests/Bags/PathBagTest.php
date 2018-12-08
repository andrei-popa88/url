<?php

namespace Keppler\Url\Test;

use Keppler\Url\Url;
use PHPUnit\Framework\TestCase;

class PathBagTest extends TestCase
{
    public function test_should_not_have_components()
    {
        $url = 'https://john.doe@www.example.com:123?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals(true, empty($urlClass->parser->getPathBag()->all()));
    }

    public function test_should_have_components()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals(['forum', 'questions'], $urlClass->parser->getPathBag()->all());
    }

    public function test_get_first_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals('forum', $urlClass->parser->getPathBag()->getFirstPathParam());
    }

    public function test_get_last_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals('assert', $urlClass->parser->getPathBag()->getLastPathParam());
    }

    public function test_has_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals(true, $urlClass->parser->getPathBag()->has(1));
    }

    public function test_does_not_have_path()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals(false, $urlClass->parser->getPathBag()->has(11));
    }

    public function test_has_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals('forum', $urlClass->parser->getPathBag()->get(0));
    }

    public function test_does_not_have_path_with_get()
    {
        $url = 'http://john.doe@www.example.com:123/forum/questions/phpunit/assert?tag=networking&order=newest#top';
        $urlClass = Url::from($url);
        $this->assertEquals(null, $urlClass->parser->getPathBag()->get(11));
    }
}