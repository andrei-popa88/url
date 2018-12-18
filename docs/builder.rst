========
Builder
========

Usage and Interface
====================

Usage
-----

.. note::

    The classes found in the Builder can be used independently too. See the Quickstart link bellow.

:doc:`Quickstart <usage>`

The Builder class is mutable. It will accept an Immutable class of the corresponding scheme.

The usage is straight forward:

.. code-block:: php

    $ftpUrl = 'ftp://user:password@host:123/path';

    $ftpImmutable = Scheme::ftp($ftpUrl);

    $builder = Builder::ftp($ftpImmutable);

    $builder->setUser('JohnDoe')
        ->setHost('example.com')
        ->setPassword('hunter2')
        ->setPort(987);

    $builder->getPathBag()
        ->set(0, 'new path value')
        ->set(1, 'another path value');

    echo $builder->raw(); // ftp://JohnDoe:hunter2@example.com:987/new path value/another path value/

    echo $builder->encoded(); // ftp://JohnDoe:hunter2@example.com:987/new+path+value/another+path+value/

