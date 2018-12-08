<?php

namespace Keppler\Url\Test;

use Keppler\Url\Url;
use PHPUnit\Framework\TestCase;

class PathBagTest extends TestCase
{
    public function canBuildFromPath()
    {
        $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';

        $urlClass = new Url($url);


    }
}