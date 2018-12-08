# Simple package to manipulate URLs

[![Build Status](https://travis-ci.org/KepplerPl/url.svg?branch=master)](https://travis-ci.org/KepplerPl/url)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

If you're looking for something more complex I recommend https://uri.thephpleague.com/

It has a lot more features that this package offers and should be used for larger websites.

-----

This package contains 2 parts. The parser and the builder.

Both are accessed using the class
```php
Keppler\Url\Url
````

## Parser

```php
require 'vendor/autoload.php';

$toParse = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';

$url = new Keppler\Url\Url($toParse);

echo $url->parser->getHost(); // www.example.com
echo $url->parser->getSchema(); // http
echo $url->parser->getAuthority(); // john.doe@www.example.com:123
...
````

The path and query are kept in separte bags and can be accessed by getting the bag

#### The query bag:

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $url->parser->getQueryBag()->getFirstQueryParam(); // networking
echo $url->parser->getQueryBag()->getLastQueryParam(); // 2015-11-12
echo $url->parser->getQueryBag()->get('tag'); // networking
...
````

#### The path bag

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $url->parser->getPathBag()->getFirstPathParam(); // forum
echo $url->parser->getPathBag()->getLastPathParam(); // questions
echo $url->parser->getPathBag()->get(0); // forum
...
````


## Instalation

```bash
composer require keppler/url
````
