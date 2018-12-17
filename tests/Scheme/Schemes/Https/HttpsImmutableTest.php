<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Https;

use Keppler\Url\Scheme\Schemes\Https\HttpsImmutable;
use PHPUnit\Framework\TestCase;

class HttpsImmutableTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testShouldInitSuccessfully()
    {
        $this->assertInstanceOf(HttpsImmutable::class,
            new HttpsImmutable($this->validUrl));
    }

    public function testGetters()
    {
        $https = new HttpsImmutable($this->validUrl);

        $this->assertEquals('john', $https->getUser());
        $this->assertEquals(HttpsImmutable::SCHEME, $https->getScheme());
        $this->assertEquals('john:password@www.example.com:123', $https->getAuthority());
        $this->assertEquals('123', $https->getPort());
        $this->assertEquals('www.example.com', $https->getHost());
        $this->assertEquals('password', $https->getPassword());
    }

    public function testGetters_onlyDomain()
    {
        $https = new HttpsImmutable('https://www.example.com');

        $this->assertEquals('', $https->getUser());
        $this->assertEquals(HttpsImmutable::SCHEME, $https->getScheme());
        $this->assertEquals('www.example.com', $https->getAuthority());
        $this->assertEquals(null, $https->getPort());
        $this->assertEquals('www.example.com', $https->getHost());
        $this->assertEquals('', $https->getPassword());
    }

    public function testGetters_InterfaceImplementation()
    {
        $https = new HttpsImmutable($this->validUrl);

        $this->assertEquals([
            'scheme' => HttpsImmutable::SCHEME,
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

        $this->assertEquals('https://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top', $https->raw());
    }

}