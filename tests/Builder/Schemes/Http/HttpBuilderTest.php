<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Http;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Builder\Schemes\Http\HttpBuilder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpBuilderTest extends TestCase
{
    private $validUrl = 'http://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testShouldInitSuccessfully()
    {
        $scheme = Scheme::http($this->validUrl);

        $builder = new HttpBuilder($scheme);
        $this->assertInstanceOf(HttpBuilder::class, $builder);
    }

    public function testGetters()
    {
        $scheme = Scheme::http($this->validUrl);
        $builder = Builder::http($scheme);

        $result = [
            'scheme' => 'http',
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
                'order' => 'newest',
            ],
            'path' => [
                'forum',
                'questions',
            ],
            'fragment' => 'top',
        ];

        $this->assertEquals($result, $builder->all());
        $this->assertEquals('http://john:password@www.example.com:123/forum/questions/?tag[0]=networking&tag[1]=music&order=newest#top',
            $builder->raw());

        $this->assertEquals(HttpBuilder::SCHEME, $builder->getScheme());
        $this->assertEquals('john:password@www.example.com:123', $builder->getAuthority());
        $this->assertEquals('top', $builder->getFragment());
        $this->assertEquals('www.example.com', $builder->getHost());
        $this->assertEquals('password', $builder->getPassword());
        $this->assertEquals(123, $builder->getPort());
        $this->assertEquals('john', $builder->getUser());
    }
}