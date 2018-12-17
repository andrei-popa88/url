<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes\Ftp;

use Keppler\Url\Scheme\Schemes\Ftp\FtpImmutable;
use PHPUnit\Framework\TestCase;

class FtpImmutableTest extends TestCase
{
    private $validUrl = 'ftp://user:password@host:123/path';


    public function testShouldInitSuccessfully()
    {
        $this->assertInstanceOf(FtpImmutable::class,
            new FtpImmutable($this->validUrl));
    }

    public function testGetters()
    {
        $ftp = new FtpImmutable($this->validUrl);

        $this->assertEquals('user', $ftp->getUser());
        $this->assertEquals(FtpImmutable::SCHEME, $ftp->getScheme());
        $this->assertEquals('123', $ftp->getPort());
        $this->assertEquals('host', $ftp->getHost());
        $this->assertEquals('password', $ftp->getPassword());
    }

    public function testGetters_onlyDomain()
    {
        $ftp = new FtpImmutable('ftp://host');

        $this->assertEquals('', $ftp->getUser());
        $this->assertEquals(FtpImmutable::SCHEME, $ftp->getScheme());
        $this->assertEquals(null, $ftp->getPort());
        $this->assertEquals('host', $ftp->getHost());
        $this->assertEquals('', $ftp->getPassword());
    }

    public function testGetters_InterfaceImplementation()
    {
        $ftp = new FtpImmutable($this->validUrl);

        $this->assertEquals([
            'scheme' => FtpImmutable::SCHEME,
            'user' => 'user',
            'password' => 'password',
            'host' => 'host',
            'port' => 123,
            'path' => [
                'path',
            ],
        ], $ftp->all());

        $this->assertEquals('ftp://user:password@host:123/path', $ftp->raw());
    }
}
