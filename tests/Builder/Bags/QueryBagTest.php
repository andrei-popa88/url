<?php

namespace Keppler\Url\Test\Builder\Bags;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Exceptions\InvalidComponentsException;
use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class QueryBagTest extends TestCase
{
    public function test_should_not_impact_build_without_components()
    {
        $url = 'https://john.doe@www.example.com:123';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals($url, ($builder->getUrl()));
    }

    public function test_should_remove_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->remove('tag');

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?order=newest#top',
            ($builder->getUrl()));
    }

    public function test_should_overwrite_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->overwrite(['tag' => 'new_value']);

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?tag=new_value&order=newest#top',
            ($builder->getUrl()));
    }

    public function test_should_append_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->append(['new_index' => 'new_value']);

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&new_index=new_value#top',
            ($builder->getUrl()));
    }

    public function test_should_prepend_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->prepend(['new_index' => 'new_value']);

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?new_index=new_value&tag=networking&order=newest#top',
            ($builder->getUrl()));
    }

    public function test_should_insert_after_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->insertAfter('tag', ['new_index' => 'new_value']);

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?tag=networking&new_index=new_value&order=newest#top',
            ($builder->getUrl()));
    }

    public function test_should_insert_before_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $builder->query->insertBefore('tag', ['new_index' => 'new_value']);

        $this->assertEquals('https://john.doe@www.example.com:123/forum/questions/?new_index=new_value&tag=networking&order=newest#top',
            ($builder->getUrl()));
    }

    public function test_should_build_correct_query()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/phpunit/exceptions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals('?tag=networking&order=newest', ($builder->query->raw()));
    }

    public function test_remove_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->query->remove('invalid_component');
    }

    public function test_overwrite_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->query->overwrite(['invalid' => 'invalid']);
    }

    public function test_insert_before_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->expectException(ComponentNotFoundException::class);

        $builder->query->insertBefore('invalid_component', []);
    }

    public function test_insert_after_should_throw_exception_when_missing_component()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->expectException(InvalidComponentsException::class);

        $builder->query->insertAfter('invalid_component', []);
    }

    public function test_can_get_all()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals(['tag' => 'networking', 'order' => 'newest'], $builder->query->all());
    }

    public function test_all_function_should_return_empty_array_even_without_url()
    {
        $builder = new Builder();

        $this->assertEquals([], $builder->query->all());
    }

    public function test_all_function_should_remove_recursive()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[baz]=baz';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals(['first' => 'value', 'arr' => ['foo bar']], $builder->query->remove('baz', true)->all());
    }

    public function test_overwrite_recursive()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[baz]=baz&arr[arr][biz]=biz&arr[][][baz]=inner_value';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals(
            [
                'first' => 'value',
                'arr' => [
                    'foo bar',
                    'baz' => 'new_value',
                    'arr' => [
                        'biz' => 'biz',
                    ],
                    1 => [
                        0 => [
                            'baz' => 'new_value'
                        ]
                    ]
                ],
            ],
            $builder->query->overwrite(['baz' => 'new_value'], true, false)->all()
        );
    }

    public function test_overwrite_recursive_stop_at_first_match()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?first=value&arr[]=foo+bar&arr[baz]=baz&arr[arr][biz]=biz&arr[][][baz]=inner_value';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);

        $this->assertEquals(
            [
                'first' => 'value',
                'arr' => [
                    'foo bar',
                    'baz' => 'new_value',
                    'arr' => [
                        'biz' => 'biz',
                    ],
                    1 => [
                        0 => [
                            'baz' => 'inner_value'
                        ]
                    ]
                ],
            ],
            $builder->query->overwrite(['baz' => 'new_value'], true, true)->all()
        );
    }
}