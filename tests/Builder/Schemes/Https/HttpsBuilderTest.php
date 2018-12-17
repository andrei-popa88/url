<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Https;

use Keppler\Url\Builder\Builder;
use Keppler\Url\Builder\Schemes\Https\HttpsBuilder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class HttpsBuilderTest extends TestCase
{
    private $validUrl = 'https://john:password@www.example.com:123/forum/questions/?tag[]=networking&order=newest&tag[]=music#top';

    public function testShouldInitSuccessfully()
    {
        $scheme = Scheme::https($this->validUrl);

        $builder = new HttpsBuilder($scheme);
        $this->assertInstanceOf(HttpsBuilder::class, $builder);
    }

    public function testGetters()
    {
        $scheme = Scheme::https($this->validUrl);
        $builder = Builder::https($scheme);

        $result = [
            'scheme' => 'https',
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
        $this->assertEquals('https://john:password@www.example.com:123/forum/questions/?tag[0]=networking&tag[1]=music&order=newest#top',
            $builder->raw());

        $this->assertEquals(HttpsBuilder::SCHEME, $builder->getScheme());
        $this->assertEquals('john:password@www.example.com:123', $builder->getAuthority());
        $this->assertEquals('top', $builder->getFragment());
        $this->assertEquals('www.example.com', $builder->getHost());
        $this->assertEquals('password', $builder->getPassword());
        $this->assertEquals(123, $builder->getPort());
        $this->assertEquals('john', $builder->getUser());
    }
}