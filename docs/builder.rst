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

.. code-block:: php

    $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $httpsImmutable = Scheme::https($url);

    $builder = Builder::https($httpsImmutable);
    ...

.. code-block:: php

    $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $httpImmutable = Scheme::http($url);

    $builder = Builder::https($httpsImmutable);
    ...

.. code-block:: php

    $url = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World';
    $mailtoImmutable = Scheme::mailto($url);

    $builder = Builder::https($mailtoImmutable);
    ...

The builder also contains bags for the query or path. Given that it can exist within the given scheme.

Some schemes don't have a path or a query. For example the FTP scheme officially does not support a query, thus
the Builder doesn't support one either.


.. code-block:: php

    $ftpUrl = 'ftp://user:password@host:123/path';
    $ftpImmutable = Scheme::ftp($ftpUrl);

    $builder = Builder::ftp($ftpImmutable);

    // Only path
    $builder->getPathBag()->...;

Other types of urls will support both a path and a query bag.

.. code-block:: php

    $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $httpsImmutable = Scheme::https($url);

    $builder = Builder::https($httpsImmutable);

    $builder->getPathBag()->...;
    $builder->getQueryBag()->...;

Builder Interface
------------------

All builders implement the ImmutableSchemeInterface which has the following functions

.. code-block:: php

    // Returns all the components of the scheme including any bags in the form of an array
    // Will always return an array, even if empty.

    public function all(): array;

    // Returns raw unaltered url

    public function raw(): string

    // Returns the scheme associated with the class instance

    public function getScheme(): string;

    // Builds the url either encoded or not
    public function build(bool $urlEncode = false): string;

.. note::

    The build function is an alias for raw() and encoded() with the $urlEncode specified as either true or false


Bags Interface
---------------

All mutable bags(query and path) implement the MutableBagsInterface which has the following functions

.. code-block:: php

    // Returns all the components of the query or path

    public function all(): array;


.. code-block:: php

    // Returns the encoded query or path string

    public function encoded(): string;

    // Return the raw unaltered query or path

    public function raw(): string;

    // Checks weather a given bag or path has a certain key

    public function has($key): bool;

    // Returns a given key

    public function get($key);

    // Sets a new entry in the path or query

    public function set($key, $value);

    // Returns all the components of the query or path

    public function all(): array;

Mailto
======

The mailto builder has a path and a query bag along side the default interface options

The mailto builder class does it's best to keep in accordance with https://tools.ietf.org/html/rfc6068

The mailto immutable has no other functions except the default implementations and getters for the bags.

The query bag
-------------

The mailto scheme can have a query consisting of: to recipients, cc recipients, bcc recipients, body, and subject.

Besides the getter functions specified in the previous chapter the builder has the following functions available.

.. code-block:: php

    public function putInTo(string $value): self

    public function putInCc(string $value): self

    public function putInBcc(string $value): self

    public function forgetFromTo($keyOrValue): self

    public function forgetFromCc($keyOrValue): self

    public function forgetFromBcc($keyOrValue): self

    public function forgetTo(): self

    public function forgetCc(): self

    public function forgetBcc(): self

    public function forgetSubject(): self

    public function forgetBody(): self

    public function toHas(string $value): bool

    public function ccHas(string $value): bool

    public function bccHas(string $value): bool

    public function forgetAll(): self

    // Returns only the specified class properties(in this case)
    public function only(string ...$args): array

.. note::

    Functions such as get and set in this particular case will search for a class property rather than a query component

.. code-block:: php

    $url
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    $mailto = Scheme::mailto($url);
    $builder = Builder::mailto($mailto);

    var_dump($builder->getQueryBag()->only('cc', 'bcc'));
    ...
    Array
    (
        [cc] => Array
            (
                [0] => email3@example.com
                [1] => email4@example.com
            )

        [bcc] => Array
            (
                [0] => email4@example.com
                [1] => email5@example.com
            )

    )

    $builder->getQueryBag()->forgetFromBcc('email5@example.com');
    $builder->getQueryBag()->forgetFromBcc(0);

    var_dump($builder->getQueryBag()->only('cc', 'bcc'));
    ...
    Array
    (
        [cc] => Array
            (
                [0] => email3@example.com
                [1] => email4@example.com
            )

        [bcc] => Array
            (
            )

    )

The path bag
-------------

Much like the query bag, the path bag comes with its own functions

.. code-block:: php

    public function setPath(array $path): self

    public function getPath(): array

    public function append(string $value): self

    public function prepend(string $value): self

    public function putInBetween(string $value, string $first = null, string $last = null): self

    public function putBefore(string $before, string $value) : self

    public function first()

    public function last()

    public function putAfter(string $after, string $value): self

    public function forget(string ...$args): self

    public function forgetAll(): self

    public function only(string ...$args): array

.. code-block:: php

    $url
        = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com'.
        '&cc=email3@example.com,email4@example.com'.
        '&bcc=email4@example.com,email5@example.com'.
        '&subject=Hello'.
        '&body=World';

    $mailto = Scheme::mailto($url);
    $builder = Builder::mailto($mailto);

    $builder->getPathBag()->putInBetween('new_value@test.com', 'path@email.com');

    var_dump($builder->getPathBag()->all());
    ...
    Array
    (
        [0] => path@email.com
        [1] => new_value@test.com
        [2] => path2@email.com
    )

    $builder->getPathBag()->putAfter('new_value@test.com', 'after_new_value@test.com');

    var_dump($builder->getPathBag()->all());
    ...
    Array
    (
        [0] => path@email.com
        [1] => new_value@test.com
        [2] => after_new_value@test.com
        [3] => path2@email.com
    )

Http and Https
==============

The http and https schemes have a path and a query bag along side the default interface options

The http and https scheme classes do their best to keep in accordance with https://tools.ietf.org/html/rfc3986

.. note::

    Due to major similarities between the 2 schemes there is a single section dedicated to both.

    HOWEVER each scheme has its own dedicated builder.

.. code-block:: php

    public function getAuthority(): string

    public function getUser(): string

    public function getPassword(): string

    public function getHost(): string

    public function getPort(): ?int

    public function getFragment(): string

    public function getQueryBag(): HttpImmutableQuery

    public function getPathBag(): HttpImmutablePath

    public function setUser(string $user): self

    public function setPassword(string $password): self

    public function setHost(string $host): self

    public function setPort(int $port): self

    public function setFragment(string $fragment): self

The query bag
--------------

Besides the default interface implementation the http/https mutable bags classes have the following functions


.. code-block:: php

    public function first(): ?array

    public function last()

    public function forget(string ...$args): self

    public function forgetAll(): self

    public function only(string ...$args): array

The path bag
-------------

Besides the default interface implementation the http/https bags bags classes have the following functions

.. code-block:: php

    public function getPath(): array

    public function first(): ?string

    public function last(): ?string

    public function append(string $value): self

    public function prepend(string $value): self

    public function putInBetween(string $value, string $first = null, string $last = null): self

    public function putBefore(string $before, string $value) : self

    public function putAfter(string $after, string $value): self

    public function forget(string ...$args): self

    public function forgetAll(): self

    public function only(string ...$args): array

Ftp
===

The ftp builder has only a path bag along side the default interface options

The ftp class does its best to keep in accordance with https://tools.ietf.org/html/rfc3986

Besides the default interface implementation the ftp mutable class has the following functions

.. code-block:: php

    public function getPathBag(): FtpMutablePath

    public function getUser(): string

    public function getPassword(): string

    public function getHost(): string

    public function getPort(): int

    public function setUser(string $user): self

    public function setPassword(string $password): self

    public function setHost(string $host): self

    public function setPort(int $port): self

The path bag
------------

Besides the default interface implementation the ftp immutable bag class has the following functions

.. code-block:: php

    public function getPath(): array

    public function first(): ?string

    public function last()

    public function append(string $value): self

    public function prepend(string $value): self

    public function putInBetween(string $value, string $first = null, string $last = null): self

    public function putBefore(string $before, string $value) : self

    public function putAfter(string $after, string $value): self

    public function forget(string ...$args): self

    public function forgetAll(): self

    public function only(string ...$args): array
