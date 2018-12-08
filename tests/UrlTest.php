<?php

namespace Keppler\Url\Test;

use Keppler\Url\Exceptions\MalformedUrlException;
use Keppler\Url\Exceptions\SchemaNotSupportedException;
use Keppler\Url\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function shouldThrowMalformedUrlException()
    {
        $this->expectException(MalformedUrlException::class);

        new Url(''); // pass in a malformed url
        new Url('example.com'); // no schema for url
    }

    public function shouldThrowSchemaNotSupportedException()
    {
        $this->expectException(SchemaNotSupportedException::class);

        new Url('news:comp.infosystems.www.servers.unix');
    }
}