<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoPathMutable;
use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class MailtoPathMutableTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    public function testShouldInitSuccessfully()
    {
        $mailto = new MailtoPathMutable();
        $this->assertInstanceOf(MailtoPathMutable::class, $mailto);
    }

    public function testGettersAndSetters()
    {

        $scheme = Scheme::mailto($this->validUrl);
        $builder = new MailtoBuilder($scheme);

        $this->assertEquals([
            'path@email.com',
            'path2@email.com',
        ], $builder->getPathBag()->getPath());


        $this->assertEquals([
            'path@email.com',
            'path2@email.com',
        ], $builder->getPathBag()->all());

        $this->assertEquals('path@email.com,path2@email.com', $builder->getPathBag()->raw());
        $this->assertEquals('path@email.com', $builder->getPathBag()->get(0));
        $this->assertEquals(true, $builder->getPathBag()->has(0));
        $this->assertEquals(false, $builder->getPathBag()->has(3));
        $this->assertEquals('path@email.com%2Cpath2@email.com', $builder->getPathBag()->encoded());

        $this->assertEquals(['path@email.com'], $builder->getPathBag()->only('path@email.com'));

        $builder->getPathBag()->prepend('test@email.com');
        $this->assertEquals([
            'test@email.com',
            'path@email.com',
            'path2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->append('test2@email.com');
        $this->assertEquals([
            'test@email.com',
            'path@email.com',
            'path2@email.com',
            'test2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->forget('test@email.com');
        $this->assertEquals([
            1 => 'path@email.com',
            2 => 'path2@email.com',
            3 => 'test2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->putAfter('path@email.com', 'new@email.com');
        $this->assertEquals([
            'path@email.com',
            'new@email.com',
            'path2@email.com',
            'test2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->putBefore('new@email.com', 'new2@email.com');
        $this->assertEquals([
            'path@email.com',
            'new2@email.com',
            'new@email.com',
            'path2@email.com',
            'test2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->putInBetween('inbetween@email.com', 'path@email.com', 'new2@email.com');
        $this->assertEquals([
            'path@email.com',
            'inbetween@email.com',
            'new2@email.com',
            'new@email.com',
            'path2@email.com',
            'test2@email.com',
        ], $builder->getPathBag()->all());

        $builder->getPathBag()->forgetAll();
        $this->assertEquals([], $builder->getPathBag()->all());
    }

    public function testShouldThrowExceptionLogic()
    {
        $scheme = Scheme::mailto($this->validUrl);
        $builder = new MailtoBuilder($scheme);

        $this->expectException(\LogicException::class);
        $builder->getPathBag()->putInBetween('inbetween@email.com');
    }

    public function testShouldThrowExceptionComponent()
    {
        $scheme = Scheme::mailto($this->validUrl);
        $builder = new MailtoBuilder($scheme);

        $this->expectException(ComponentNotFoundException::class);
        $builder->getPathBag()->putInBetween('inbetween@email.com', 'invalid');
    }
}