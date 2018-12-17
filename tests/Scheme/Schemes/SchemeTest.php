<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Scheme\Schemes;

use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class SchemeTest extends TestCase
{
    public function testShouldInitSuccessfully()
    {
        $this->assertInstanceOf(Scheme::class, new Scheme());
    }

    public function testShouldThrowInvalidArgumentException_Mailto()
    {
        $this->expectException(\InvalidArgumentException::class);

        Scheme::mailto('mailto_');
    }

    public function testShouldThrowInvalidArgumentException_Https()
    {
        $this->expectException(\InvalidArgumentException::class);

        Scheme::mailto('https_');
    }

    public function testShouldThrowInvalidArgumentException_Http()
    {
        $this->expectException(\InvalidArgumentException::class);

        Scheme::mailto('http_');
    }

    public function testShouldThrowInvalidArgumentException_Ftp()
    {
        $this->expectException(\InvalidArgumentException::class);

        Scheme::mailto('ftp_');
    }
}