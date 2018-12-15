<?php
declare(strict_types=1);

namespace Kepper\Url\Tests\Unit\Scheme\Schemes\Ftp\Bags;

use Keppler\Url\Scheme\Schemes\Ftp\Bags\FtpImmutablePath;
use PHPUnit\Framework\TestCase;

class FtpImmutablePathTest extends TestCase
{
    private $validUrl = 'ftp://user:password@host:123/path';

    public function testGettersEmptyUrl()
    {
        $ftp = new FtpImmutablePath();

        $this->assertEquals('', $ftp->raw());
        $this->assertEquals([], $ftp->all());
        $this->assertEquals(null, $ftp->last());
        $this->assertEquals(null, $ftp->first());
        $this->assertEquals(false, $ftp->has(0));
    }

    public function testGettersNotEmptyUrl()
    {
        $parsed = parse_url($this->validUrl);
        $ftp = new FtpImmutablePath($parsed['path']);

        $this->assertEquals('/path', $ftp->raw());
        $this->assertEquals([
            'path',
        ], $ftp->all());
        $this->assertEquals('path', $ftp->last());
        $this->assertEquals('path', $ftp->first());
        $this->assertEquals(true, $ftp->has(0));

    }
}