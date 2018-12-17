=====
Usage
=====

Overview
--------

This package is split into 2 parts

.. code-block:: bash

 Scheme - src/Scheme/Scheme.php

.. code-block:: bash

 Builder - src/Builder/Builder.php

.. note::
    The Scheme is, for all intents and purposes, immutable. Barring reflection.

Each of the 2 parts is split again into schemes.

Currently there are 4 schemes available:

.. code-block:: php

 Scheme::mailto();
 Scheme::https();
 Scheme::http();
 Scheme::ftp();

.. note::
    Soon an unknown scheme will be added as a general purpose scheme

The builder will accept an instance of the Scheme class which will remain immutable.
It will simply extract information from it and nothing else.

Using the builder you can modify whatever information was received.