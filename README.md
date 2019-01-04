# Simple package to manipulate URLs

[![Build Status](https://travis-ci.org/KepplerPl/url.svg?branch=master)](https://travis-ci.org/KepplerPl/url)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Documentation Status](https://readthedocs.org/projects/kepplerpl-scheme/badge/?version=latest)](https://kepplerpl-scheme.readthedocs.io/en/latest/?badge=latest)
[![Maintainability](https://api.codeclimate.com/v1/badges/a99a88d28ad37a79dbf6/maintainability)](https://codeclimate.com/github/codeclimate/codeclimate/maintainability)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KepplerPl/url/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KepplerPl/url/?branch=master)

## [Read the docs documentation](https://kepplerpl-scheme.readthedocs.io/en/latest/)

### Installation

```
composer require keppler/url
```


Quickstart
----------

Parser and Builder
----------

This package is split into 2 independent pieces. These pieces are also split into several other pieces.

    The Scheme.php will be referred to as Parser in the documentation.

Parser
------

The Parser is the entry point for parsing a url. It's immutable, meaning you cannot change it once it is created.

The scheme is split into schemes such as ftp, http, https, mailto, etc.
Each scheme is used to parse a single url type, as you might have guessed.

```php
require 'vendor/autoload.php';

$url =  'https://john.doe@www.example.com:123/forum/questions/answered/latest?tag=networking&order=newest#top';

$scheme = Scheme::https($url);

print_r($scheme->all());

echo $scheme->getPathBag()->first(); // forum
echo $scheme->getPathBag()->last(); // latest
echo $scheme->getPathBag()->raw(); // /forum/questions/answered/latest
echo $scheme->getPathBag()->get(1); // questions
echo $scheme->getPathBag()->has(0); // true
echo $scheme->getPathBag()->has(10); // false


var_dump($scheme->getQueryBag()->first()); // ['tag' => 'networking']

etc
````

At the time of this writing the parser supports 4 schemes: FTP, HTTPS, HTTP, and MAILTO

Builder
-------

The Builder.php class is the entry point for modifying a url or simply creating one from scratch.
If you choose to build from an existing url you must pass it a Parser instance with the appropriate scheme.

At the time of this writing the Builder supports 4 schemes: FTP, HTTPS, HTTP, and MAILTO

```php
require 'vendor/autoload.php';

$url =  'ftp://user:password@host:123/path';

$ftpScheme = Scheme::ftp($url);
$builder = Builder::ftp($ftpScheme);

$builder->setHost('example.com')
    ->setPassword('hunter2')
    ->setPort(5);

print_r($builder->raw());
...
ftp://user:hunter2@example.com:5/path/

print_r($builder->encoded());
...
ftp://user:hunter2@example.com:5/path/to+encode/ // notice the extra +
````

Both the Parser and the Builder can be used independently.

Each supported scheme can also be used independently without the Builder or the Parser. Examples bellow.

Independent usage
-----------------

Assuming you don't want to use the Parser/Builder classes directly you can choose not to.

Each scheme supported can be used independently of the Parser/Builder.

```php
$ftpUrl = 'ftp://user:password@host:123/path';

$ftpImmutable = new FtpImmutable($ftpUrl);

echo $ftpImmutable->raw();

$ftpBuilder = new FtpBuilder();

$ftpBuilder->setHost('host')
    ->setPassword('hunter2')
    ->setPort(987)
    ->setUser('hunter');

$ftpBuilder->getPathBag()
    ->set(0, 'path')
    ->set(1, 'new path');

echo $ftpBuilder->raw(); // ftp://hunter:hunter2@host:987/path/new path/

echo $ftpBuilder->encoded(); // ftp://hunter:hunter2@host:987/path/new+path/
````
