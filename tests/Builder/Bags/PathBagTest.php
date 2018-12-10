<?php

namespace Keppler\Url\Test\Builder\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class PathBagTest extends TestCase
{
    public function test_should_not_impact_build_without_components()
    {
        $url = 'https://john.doe@www.example.com:123?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->assertEquals($url, ($builder->getUrl()));
    }

    public function test_should_remove_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $builder->path->remove(0);
        $this->assertEquals('https://john.doe@www.example.com:123/questions/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_overwrite_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->overwrite(0, 'new_component');

        $this->assertEquals('https://john.doe@www.example.com:123/new_component/questions/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_append_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->append('new_component');

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/new_component/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_prepend_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->prepend('new_component');

        $this->assertEquals('https://john.doe@www.example.com:123/new_component/forum/questions/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_insert_after_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/phpunit/exceptions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->insertAfter(1, 'new_value');

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/new_value/phpunit/exceptions/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_insert_before_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/phpunit/exceptions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->insertBefore(1, 'new_value');

        $this->assertEquals('https://john.doe@www.example.com:123/forum/new_value/questions/phpunit/exceptions/?tag=networking&order=newest#top', ($builder->getUrl()));
    }

    public function test_should_build_correct_path()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/phpunit/exceptions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);
        $builder->path->raw();

        $this->assertEquals('/forum/questions/phpunit/exceptions/', ($builder->path->raw()));
    }

    public function test_remove_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->path->remove(10);
    }

    public function test_overwrite_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->path->overwrite(10 /* invalid offset */, '');
    }

    public function test_insert_before_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->path->insertBefore(10 /* invalid offset */, '');
    }

    public function test_insert_after_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->path->insertAfter(10 /* invalid offset */, '');
    }

    public function test_can_get_all()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = new Parser($url);
        $builder = Builder::from($parser);

        $this->assertEquals(['forum', 'questions'], $builder->path->all());
    }

    public function test_all_function_should_return_empty_array_even_without_url()
    {
        $builder = new Builder();

        $this->assertEquals([], $builder->path->all());
    }
}