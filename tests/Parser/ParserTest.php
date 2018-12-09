<?php

namespace Keppler\Parser\Tests;

use Keppler\Url\Parser\Bags\PathBag;
use Keppler\Url\Parser\Bags\QueryBag;
use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Exceptions\SchemaNotSupportedException;
use Keppler\Url\Parser\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function test_should_throw_malformed_exception()
    {
        $this->expectException(MalformedUrlException::class);

        Parser::from(''); // pass in a malformed url
    }

    public function test_should_throw_schema_not_supported_exception()
    {
        $this->expectException(SchemaNotSupportedException::class);

        Parser::from('news:comp.infosystems.www.servers.unix');
    }

    public function test_should_be_instance_of_path_bag()
    {
        $parser = Parser::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(PathBag::class, $parser->path);
    }

    public function test_should_be_instance_of_query_bag()
    {
        $parser = Parser::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertInstanceOf(QueryBag::class, $parser->query);
    }

    public function test_should_have_schema_https()
    {
        $parser = Parser::from('https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('https', $parser->getSchema());
    }

    public function test_should_have_schema_http()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('http', $parser->getSchema());
    }

    public function test_should_have_schema_mailto()
    {
        $parser = Parser::from('mailto:John.Doe@example.com');

        $this->assertEquals('mailto', $parser->getSchema());
    }

    public function test_should_full_authority()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('john.doe@www.example.com:123', $parser->getAuthority());
    }

    public function test_should_authority_without_userinfo()
    {
        $parser = Parser::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com:123', $parser->getAuthority());
    }

    public function test_should_authority_without_port()
    {
        $parser = Parser::from('http://www.example.com/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('www.example.com', $parser->getAuthority());
    }

    public function test_should_have_fragment()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top');

        $this->assertEquals('top', $parser->getFragment());
    }

    public function test_should_have_only_one_fragment()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('top', $parser->getFragment());
    }

    public function test_should_not_have_fragment()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest');

        $this->assertEquals(null, $parser->getFragment());
    }

    public function test_should_have_username()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('john.doe', $parser->getUsername());
    }

    public function test_should_not_have_username()
    {
        $parser = Parser::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $parser->getUsername());
    }

    public function test_should_have_host()
    {
        $parser = Parser::from('http://www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('www.example.com', $parser->getHost());
    }

    public function test_should_have_password()
    {
        $parser = Parser::from('http://john.doe:password@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals('password', $parser->getPassword());
    }

    public function test_should_not_have_password()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $parser->getPassword());
    }

    public function test_should_have_port()
    {
        $parser = Parser::from('http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(123, $parser->getPort());
    }

    public function test_should_not_have_port()
    {
        $parser = Parser::from('http://john.doe@www.example.com/forum/questions/?tag=networking&order=newest#top#test');

        $this->assertEquals(null, $parser->getPort());
    }
}