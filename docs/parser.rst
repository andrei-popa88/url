==========
Parser
==========

Usage and Interface
====================

Usage
-----

The Scheme.php class is used as the parser.
Next we'll talk about the supported schemes.

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

.. warning::

    If a query is given to a scheme that doesn't support it, it will be discarded

    If a path is given to a scheme that doesn't support it, it will be discarded

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

The mailto scheme does it's best to keep in accordance with https://tools.ietf.org/html/rfc6068

Keeping that in mind a mailto scheme should look like so

.. code-block:: bash

    mailtoURI    = "mailto:" [ to ] [ hfields ]
    to           = addr-spec *("," addr-spec )
    hfields      = "?" hfield *( "&" hfield )
    hfield       = hfname "=" hfvalue
    hfname       = *qchar
    hfvalue      = *qchar
    addr-spec    = local-part "@" domain
    local-part   = dot-atom-text / quoted-string
    domain       = dot-atom-text / "[" *dtext-no-obs "]"
    dtext-no-obs = %d33-90 / ; Printable US-ASCII
    %d94-126  ; characters not including
    ; "[", "]", or "\"
    qchar        = unreserved / pct-encoded / some-delims
    some-delims  = "!" / "$" / "'" / "(" / ")" / "*"
    / "+" / "," / ";" / ":" / "@"


.. warning::

    The parser makes absolutely no promises regarding the validity of the scheme, potential malformed urls
    or other such things.

The query bag
-------------


.. warning::

    The query bag may not always contain something. Most mailto urls don't usually have a path or a query for that matter.
    They're much more simplistic. You can still use the path and/or query bag, but they'll just return empty strings.


The mailto scheme can have a query consisting of: to recipients, cc recipients, bcc recipients, body, and subject.

.. code-block:: php

    mailto:path@email.com,path2@email.com?to=email@example.com,email2@example.com&cc=email3@example.com,email4@example.com&bcc=email4@example.com,email5@example.com&subject=Hello&body=World

Here we have a full mailto scheme, path included.

Each part of the mailto scheme has it's own dedicated functions.

Example:

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


.. warning::

    The path bag may not always contain something. Most mailto urls don't usually have a path or a query for that matter.
    They're much more simplistic. You can still use the path and/or query bag, but they'll just return empty strings.

.. code-block:: php

    public function first()

    public function last()

    public function hasInPath(string $value): bool

    public function getPath(): array
