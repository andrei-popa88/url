==========
Quickstart
==========

Parser and Builder
==================

This package is split into 2 independent pieces. These pieces are also split into several other pieces.

.. note::

    The Scheme.php will be referred to as Parser in the documentation.

Parser
------

The Parser is the entry point for parsing a url. It's immutable, meaning you cannot change it once it is created.

The scheme is split into schemes such as ftp, http, https, mailto, etc.
Each scheme is used to parse a single url type, as you might have guessed.

.. code-block:: php

    require 'vendor/autoload.php';

    $url =  'ftp://user:password@host:123/path';

    $scheme = Scheme::ftp($url);

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

At the time of this writing the parser supports 4 schemes: FTP, HTTPS, HTTP, and MAILTO

Builder
-------

The Builder.php class is the entry point for modifying a url or simply creating one from scratch.
If you choose to build from an existing url you must pass it a Parser instance with the appropriate scheme.

At the time of this writing the Builder supports 4 schemes: FTP, HTTPS, HTTP, and MAILTO

.. code-block:: php

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


.. note::

    Both the Parser and the Builder can be used independently.

    Each supported scheme can also be used independently without the Builder or the Parser. Examples bellow.

Independent usage
-----------------

Assuming you don't want to use the Parser/Builder classes directly you can choose not to.

Each scheme supported can be used independently of the Parser/Builder.

.. code-block:: php

    $ftpUrl = 'ftp://user:password@host:123/path';

    $ftpImmutable = new FtpImmutable($ftpUrl);

    echo $ftpImmutable->raw();

.. code-block:: php

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