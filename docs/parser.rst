==========
Parser
==========

Usage and Interface
====================

Usage
-----

.. warning::

    PLEASE READ!

    The parser makes absolutely no promises regarding the validity of the scheme nor does it try to parse severely malformed urls.

    Passing such urls to the parser will most likely result in an error.


    If a query or path is given to a scheme that doesn't support it, it will be discarded


    Some url schemes MAY not have information in the path/query bag since some urls can simply not have a path or a query.
    For example the mailto scheme may not have a query or a path, or both.
    The ftp scheme simply doesn't support a query so the parse will automatically discard it if one is given.

    The path and/or query bags will ALWAYS exist but they may not contain any information.

The Scheme.php class is used as the parser. Any Parser instance is immutable, meaning you cannot change it once it has been created.

The usage is straight forward:

.. code-block:: php

    $url =  'ftp://user:password@host:123/path';
    $ftp = Scheme::ftp($url);

.. code-block:: php

    $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $https = Scheme::https($url);

.. code-block:: php

    $url = 'http://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $http = Scheme::http($url);

.. code-block:: php

    $url = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World';
    $mailto = Scheme::mailto($url);


The parser also contains bags for the query or path. Given that it can exist within the given scheme.

Some schemes don't have a path or a query. For example the FTP scheme officially does not support a query, thus
the Parser doesn't support one either.


.. code-block:: php

    $url =  'ftp://user:password@host:123/path';
    $ftp = Scheme::ftp($url);

    // Only path
    $ftp->getPathBag()->...;

Other types of urls will support both a path and a query bag.

.. code-block:: php

    $url = 'https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top';
    $https = Scheme::https($url);

    $https->getPathBag()->...;
    $https->getQueryBag()->...;

Parser Interface
------------------

All parsers implement the ImmutableSchemeInterface which has the following functions


.. code-block:: php

    // Returns all the components of the scheme including any bags in the form of an array
    // Will always return an array, even if empty.

    public function all(): array;

.. code-block:: php

    // Returns raw unaltered url

    public function raw(): string

.. code-block:: php

    // Returns the scheme associated with the class instance

    public function getScheme(): string;

Bags Interface
---------------

All immutable bags(query and path) implement the ImmutableBagInterface which has the following functions

.. code-block:: php

    // Returns all the components of the query or path

    public function all(): array;


.. code-block:: php

    // Return the raw unaltered query or path

    public function raw(): string;


Mailto
======

The mailto scheme has a path and a query bag along side the default interface options

The mailto scheme class does it's best to keep in accordance with https://tools.ietf.org/html/rfc6068

The mailto immutable has no other functions except the default implementations and getters for the bags.

The query bag
-------------

The mailto scheme can have a query consisting of: to recipients, cc recipients, bcc recipients, body, and subject.

.. code-block:: php

    $url = 'mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World';
    $mailto = Scheme::mailto($url);
    echo $mailto->getQueryBag()->firstInTo(); // email@example.com
    echo $mailto->getQueryBag()->lastInTo(); // email2@example.com
    echo $mailto->getQueryBag()->hasInTo('email@example.com'); // true
    echo $mailto->getQueryBag()->hasInTo('not_in_to@example.com'); // false

The same goes for CC and BCC functions with the only difference being the suffix of the function

Besides the to, cc, and bcc functions getters are available for subject and body

.. code-block:: php

    public function getSubject(): string

    public function getBody(): string

    public function getBcc(): array

    public function getCc(): array

    public function getTo(): array


The path bag
-------------

Much like the query bag, the path bag comes with its own functions

.. code-block:: php

    public function first()

    public function last()

    public function hasInPath(string $value): bool

    public function getPath(): array

Due to the simplicity of the path in mailto schemes the path bag is not very feature rich.

Http and Https
==============

The http and https schemes have a path and a query bag along side the default interface options

The http and https scheme classes do their best to keep in accordance with https://tools.ietf.org/html/rfc3986

.. note::

    Due to major similarities between the 2 schemes there is a single section dedicated to both.

    HOWEVER each scheme has its own dedicated parser.

Besides the default interface implementation the http and https immutable classes have the following functions

.. code-block:: php

    public function getAuthority(): string

    public function getUser(): string

    public function getPassword(): string

    public function getHost(): string

    public function getPort(): ?int

    public function getFragment(): string

    public function getQueryBag(): HttpImmutableQuery

    public function getPathBag(): HttpImmutablePath


The query bag
--------------

Besides the default interface implementation the http/https immutable bags class has the following functions

.. code-block:: php

    $url = 'http://john:password@www.example.com:123/forum/questions 10/?&tag[]=networking&tag[]=cisco&order=newest#top';

    $scheme = Scheme::http($url);

    var_dump($scheme->getQueryBag()->get('tag'));

    ...

    Array
    (
        [0] => networking
        [1] => cisco
    )


.. code-block:: php

    public function get($key)

    public function has($key): bool

    public function first(): ?array

    public function last(): ?string

The path bag
-------------

Besides the default interface implementation the http/https immutable bags class has the following functions

.. code-block:: php

    $url = 'http://john:password@www.example.com:123/forum/questions 10/?&tag[]=networking&tag[]=cisco&order=newest#top';

    $scheme = Scheme::http($url);

    var_dump($scheme->getPathBag()->get(0));

    ...

    string(5) "forum"

    $scheme->getPathBag()->get(10);

    ...

    Fatal error:  Uncaught Keppler\Url\Exceptions\ComponentNotFoundException: Component with index "10" does not exist in Keppler\Url\Scheme\Schemes\Http\Bags\HttpImmutablePath

.. code-block:: php

    public function first(): ?string

    public function last(): ?string

    public function get(int $key)

    public function has(int $key): bool


Ftp
===

The ftp parser has only a path bag along side the default interface options

The ftp class does its best to keep in accordance with https://tools.ietf.org/html/rfc3986

Besides the default interface implementation the ftp immutable class has the following functions

.. code-block:: php

    public function getPathBag(): FtpImmutablePath

    public function getUser(): string

    public function getPassword(): string

    public function getHost(): string

    public function getPort(): ?int

The path bag
------------

Besides the default interface implementation the ftp immutable bag class has the following functions


.. code-block:: php

    public function first(): ?string

    public function last(): ?string

    public function get(int $key)

    public function has(int $key): bool
