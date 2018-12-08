<?php

namespace Keppler\Url\Tests;

use Keppler\Url\Bags\PathBag;
use Keppler\Url\Bags\QueryBag;
use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Exceptions\SchemaNotSupportedException;
use Keppler\Url\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function test_should_throw_malformed_exception()
    {
        $this->expectException(MalformedUrlException::class);

        new Url(''); // pass in a malformed url
        new Url('example.com'); // no schema for url
    }

    public function test_should_throw_schema_not_supported_exception()
    {
        $this->expectException(SchemaNotSupportedException::class);

        new Url('news:comp.infosystems.www.servers.unix');
    }

    public function test_should_be_instance_of_path_bag()
    {
        $urlClass = new Url('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(PathBag::class, $urlClass->parser->getPathBag());
    }

    public function test_should_be_instance_of_query_bag()
    {
        $urlClass = new Url('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(QueryBag::class, $urlClass->parser->getQueryBag());
    }

    public function test_should_have_schema_https()
    {
        $urlClass = new Url('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('https', $urlClass->parser->getSchema());
    }

    public function test_should_have_schema_http()
    {
        $urlClass = new Url('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('http', $urlClass->parser->getSchema());
    }

    public function test_should_have_schema_mailto()
    {
        $urlClass = new Url('mailto:John.Doe@example.com');

        $this->assertEquals('mailto', $urlClass->parser->getSchema());
    }

    public function test_should_full_authority()
    {
        $urlClass = new Url('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('john.doe@www.example.com:123', $urlClass->parser->getAuthority());
    }

    public function test_should_authority_without_userinfo()
    {
        $urlClass = new Url('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com:123', $urlClass->parser->getAuthority());
    }

    public function test_should_authority_without_port()
    {
        $urlClass = new Url('http://www.example.com/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com', $urlClass->parser->getAuthority());
    }

    public function test_should_have_fragment()
    {
        $urlClass = new Url('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('top', $urlClass->parser->getFragment());
    }
}