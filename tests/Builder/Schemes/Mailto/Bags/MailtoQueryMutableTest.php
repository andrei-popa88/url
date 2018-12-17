<?php
declare(strict_types=1);

namespace Keppler\Url\Tests\Builder\Schemes\Mailto\Bags;

use Keppler\Url\Builder\Schemes\Mailto\Bags\MailtoQueryMutable;
use Keppler\Url\Builder\Schemes\Mailto\MailtoBuilder;
use Keppler\Url\Scheme\Scheme;
use PHPUnit\Framework\TestCase;

class MailtoQueryMutableTest extends TestCase
{
    private $validUrl
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';


    public function testInitSuccessfully()
    {
        $mailto = new MailtoQueryMutable();
        $this->assertInstanceOf(MailtoQueryMutable::class, $mailto);
    }

    public function testGettersAndSetters()
    {

        $scheme = Scheme::mailto($this->validUrl);
        $builder = new MailtoBuilder($scheme);

        $this->assertEquals([
            'to'      => [
                'email@example.com',
                'email2@example.com',
            ],
            'cc'      => [
                'email3@example.com',
                'email4@example.com',
            ],
            'bcc'     => [
                'email4@example.com',
                'email5@example.com',
            ],
            'subject' => 'Hello',
            'body'    => 'World',
        ], $builder->getQueryBag()->all());

        $this->assertEquals('?to=email@example.com%2Cemail2@example.com&cc=email3@example.com%2Cemail4@example.com&bcc=email4@example.com%2Cemail5@example.com&subject=Hello&body=World',
            $builder->getQueryBag()->encoded());


        $this->assertEquals('?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World',
            $builder->getQueryBag()->raw());

        $this->assertEquals(true, $builder->getQueryBag()->has('cc'));
        $this->assertEquals('Hello', $builder->getQueryBag()->get('subject'));
        $this->assertEquals('email@example.com', $builder->getQueryBag()->getInTo(0));
        $this->assertEquals('email3@example.com', $builder->getQueryBag()->getInCc(0));
        $this->assertEquals('email4@example.com', $builder->getQueryBag()->getInBcc(0));

        $this->assertEquals(true, $builder->getQueryBag()->bccHas('email4@example.com'));
        $this->assertEquals(false, $builder->getQueryBag()->bccHas('invalid'));

        $this->assertEquals(true, $builder->getQueryBag()->ccHas('email3@example.com'));
        $this->assertEquals(false, $builder->getQueryBag()->bccHas('invalid'));

        $this->assertEquals(true, $builder->getQueryBag()->toHas('email@example.com'));
        $this->assertEquals(false, $builder->getQueryBag()->bccHas('invalid'));

        $this->assertEquals('email@example.com', $builder->getQueryBag()->firstInTo());
        $this->assertEquals('email2@example.com', $builder->getQueryBag()->lastInTo());

        $this->assertEquals('email3@example.com', $builder->getQueryBag()->firstInCc());
        $this->assertEquals('email4@example.com', $builder->getQueryBag()->lastInCc());

        $this->assertEquals('email4@example.com', $builder->getQueryBag()->firstInBcc());
        $this->assertEquals('email5@example.com', $builder->getQueryBag()->lastInBcc());

        $builder->getQueryBag()->forgetFromTo('email@example.com');
        $this->assertEquals([ 1 => 'email2@example.com'], $builder->getQueryBag()->getTo());

        $builder->getQueryBag()->forgetFromCc('email3@example.com');
        $this->assertEquals([ 1 => 'email4@example.com'], $builder->getQueryBag()->getCc());

        $builder->getQueryBag()->forgetFromBcc('email4@example.com');
        $this->assertEquals([ 1 => 'email5@example.com'], $builder->getQueryBag()->getBcc());

        $builder->getQueryBag()->forgetTo();
        $this->assertEquals([], $builder->getQueryBag()->getTo());

        $builder->getQueryBag()->forgetCc();
        $this->assertEquals([], $builder->getQueryBag()->getCc());

        $builder->getQueryBag()->forgetBcc();
        $this->assertEquals([], $builder->getQueryBag()->getBcc());
    }
}