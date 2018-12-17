<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Http;

use Keppler\Url\Scheme\Schemes\Http\HttpImmutable;
use PHPUnit\Framework\TestCase;

class HttpImmutableTest extends TestCase
{
    private $validUrl = 'http://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testShouldInitSuccessfully()
    {
        $this->assertInstanceOf(HttpImmutable::class,
            new HttpImmutable($this->validUrl));
    }

    public function testGetters()
    {
        $https = new HttpImmutable($this->validUrl);

        $this->assertEquals('john', $https->getUser());
        $this->assertEquals(HttpImmutable::SCHEME, $https->getScheme());
        $this->assertEquals('john:password@www.example.com:123', $https->getAuthority());
        $this->assertEquals('123', $https->getPort());
        $this->assertEquals('www.example.com', $https->getHost());
        $this->assertEquals('password', $https->getPassword());
    }

    public function testGetters_onlyDomain()
    {
        $https = new HttpImmutable('https://www.example.com');

        $this->assertEquals('', $https->getUser());
        $this->assertEquals(HttpImmutable::SCHEME, $https->getScheme());
        $this->assertEquals('www.example.com', $https->getAuthority());
        $this->assertEquals(null, $https->getPort());
        $this->assertEquals('www.example.com', $https->getHost());
        $this->assertEquals('', $https->getPassword());
    }

    public function testGetters_InterfaceImplementation()
    {
        $https = new HttpImmutable($this->validUrl);

        $this->assertEquals([
            'scheme' => HttpImmutable::SCHEME,
            'authority' => 'john:password@www.example.com:123',
            'user' => 'john',
            'password' => 'password',
            'host' => 'www.example.com',
            'port' => 123,
            'query' => [
                'tag' => [
                    'networking',
                    'music'
                ],
                'order' => 'newest'
            ],
            'path' => [
                'forum',
                'questions'
            ],
            'fragment' => 'top',
        ], $https->all());

        $this->assertEquals('http://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top', $https->raw());
    }

}