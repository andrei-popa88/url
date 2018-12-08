# Simple package to manipulate URLs

[![Build Status](https://travis-ci.org/KepplerPl/url.svg?branch=master)](https://travis-ci.org/KepplerPl/url)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

If you're looking for something more complex I recommend https://uri.thephpleague.com/

It has a lot more features that this package offers and should be used for larger websites.

-----

This package contains 2 parts. The parser and the builder.

## Parser

The parser is immutable

```php
require 'vendor/autoload.php';

$urlString = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top';

$parser = Parser::from($urlString);

echo $parser->getHost(); // www.example.com
echo $parser->getSchema(); // http
echo $parser->getAuthority(); // john.doe@www.example.com:123

// you can also do
echo Parser::from($urlString)->getHost(); // www.example.com
echo Parser::from($urlString)->getSchema(); // http
echo Parser::from($urlString)->getAuthority(); // john.doe@www.example.com:123

// But this will create a new class instance every time
````

The path and query are kept in separte bags and can be accessed by getting the bag

#### The query bag:

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $parser->query->first(); // networking
echo $parser->query->last(); // 2015-11-12
echo $parser->query->get('tag'); // networking
echo $parser->query->original(); // tag=networking&order=newest&date=2015-11-12
...
````

#### The path bag

```php
http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest&date=2015-11-12#top

echo $parser->path->first(); // forum
echo $parser->path->last(); // questions
echo $parser->path->get(0); // forum
echo $parser->path->original(); // /forum/questions/
...
````


## Instalation

```bash
composer require keppler/url
````
