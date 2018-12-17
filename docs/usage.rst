Quickstart
==========

Parser and Builder
===========================================

This package is split into 2 independent pieces. These pieces are also split into several other pieces.

Scheme
------

The Scheme.php class is the entry point for parsing a url. It's immutable, barring reflection.
It's split into schemes such as ftp, http, https, mailto, etc.
Each scheme is used to parse a single url type, as you might have guessed.

.. code-block:: php

    require 'vendor/autoload.php';

    $url =  'ftp://user:password@host:123/path';

    $scheme = \Keppler\Url\Scheme\Scheme::ftp($url);

    print_r($scheme->all());

    ...

    Array
    (
        [scheme] => ftp
        [user] => user
        [password] => password
        [host] => host
        [port] => 123
        [path] => Array
            (
                [0] => path
            )

    )

Builder
-------

The Builder.php class is the entry point for modifying a url or simply creating one from scratch.
If you choose to build from an existing url you must pass it a Scheme instance with the appropriate scheme.


.. code-block:: php

    require 'vendor/autoload.php';

    $url =  'ftp://user:password@host:123/path';

    $ftpScheme = \Keppler\Url\Scheme\Scheme::ftp($url);
    $builder = \Keppler\Url\Builder\Builder::ftp($ftpScheme);

    $builder->setHost('example.com')
        ->setPassword('hunter2')
        ->setPort(5);

    print_r($builder->raw());
    ...
    ftp://user:hunter2@example.com:5/path/

    print_r($builder->encoded());
    ...
    ftp://user:hunter2@example.com:5/path/to+encode/ // notice the extra +
