<?php

namespace Keppler\Parser\Tests;

use Keppler\Url\Builder\Bags\PathBag;
use Keppler\Url\Builder\Bags\QueryBag;
use Keppler\Url\Builder\Builder;
use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Exceptions\SchemeNotSupportedException;
use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function test_should_throw_malformed_exception()
    {
        $this->expectException(MalformedUrlException::class);

        $parser = Parser::from('');
        Builder::from($parser);
    }

    public function test_can_instantiante_without_parser_class()
    {
        $builder = new Builder();

        $this->assertInstanceOf(PathBag::class, $builder->path);
        $this->assertInstanceOf(QueryBag::class, $builder->query);
    }

    public function test_invalid_scheme()
    {
        $this->expectException(SchemeNotSupportedException::class);

        $builder = new Builder();
        $builder->setScheme('invalid_scheme');
    }

    public function test_build_without_scheme_or_host()
    {
        $this->expectException(\LogicException::class);

        $builder = new Builder();
        $builder->getUrl();
    }

    public function test_url_builds_correctly()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $this->assertEquals($url, $builder->getUrl(true));
    }

    public function test_with_trailing_slash()
    {
        $url = 'https://www.example.com/forum/questions';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $this->assertEquals($url . '/', $builder->getUrl(true));
    }

    public function test_without_trailing_slash()
    {
        $url = 'https://www.example.com/forum/questions';
        $parser = Parser::from($url);
        $builder = Builder::from($parser);
        $this->assertEquals($url, $builder->getUrl(false));
    }
}
