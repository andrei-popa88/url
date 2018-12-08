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

        Url::from(''); // pass in a malformed url
        Url::from('example.com'); // no schema for url
    }

    public function test_should_throw_schema_not_supported_exception()
    {
        $this->expectException(SchemaNotSupportedException::class);

        Url::from('news:comp.infosystems.www.servers.unix');
    }

    public function test_should_be_instance_of_path_bag()
    {
        $urlClass = Url::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(PathBag::class, $urlClass->parser->getPathBag());
    }

    public function test_should_be_instance_of_query_bag()
    {
        $urlClass = Url::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(QueryBag::class, $urlClass->parser->getQueryBag());
    }

    public function test_should_have_schema_https()
    {
        $urlClass = Url::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('https', $urlClass->parser->getSchema());
    }

    public function test_should_have_schema_http()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('http', $urlClass->parser->getSchema());
    }

    public function test_should_have_schema_mailto()
    {
        $urlClass = Url::from('mailto:John.Doe@example.com');

        $this->assertEquals('mailto', $urlClass->parser->getSchema());
    }

    public function test_should_full_authority()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('john.doe@www.example.com:123', $urlClass->parser->getAuthority());
    }

    public function test_should_authority_without_userinfo()
    {
        $urlClass = Url::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com:123', $urlClass->parser->getAuthority());
    }

    public function test_should_authority_without_port()
    {
        $urlClass = Url::from('http://www.example.com/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com', $urlClass->parser->getAuthority());
    }

    public function test_should_have_fragment()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('top', $urlClass->parser->getFragment());
    }

    public function test_should_have_only_one_fragment()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('top', $urlClass->parser->getFragment());
    }

    public function test_should_not_have_fragment()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest');

        $this->assertEquals(null, $urlClass->parser->getFragment());
    }

    public function test_should_have_username()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('john.doe', $urlClass->parser->getUsername());
    }

    public function test_should_not_have_username()
    {
        $urlClass = Url::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $urlClass->parser->getUsername());
    }

    public function test_should_have_host()
    {
        $urlClass = Url::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('www.example.com', $urlClass->parser->getHost());
    }

    public function test_should_have_password()
    {
        $urlClass = Url::from('http://john.doe:password@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('password', $urlClass->parser->getPassword());
    }

    public function test_should_not_have_password()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $urlClass->parser->getPassword());
    }

    public function test_should_have_port()
    {
        $urlClass = Url::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(123, $urlClass->parser->getPort());
    }

    public function test_should_not_have_port()
    {
        $urlClass = Url::from('http://john.doe@www.example.com/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $urlClass->parser->getPort());
    }
}