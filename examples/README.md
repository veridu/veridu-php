Examples
========

Here you'll find pieces of code to help you use the SDK.

apicall.php
-----------
This example demonstrates how to perform an API request

autoload.php.dist
-----------------
This is an `autoloader` (class loader), in case you are not using `Composer` to install this package.
To use this `autoloader`, rename it to autoload.php and change the `require_once` directive on the example files.

logged.php
----------
Common workflow for logged users

session.php
-----------
This example demonstrates how to handle a read/write session, for logged in users

settings.php.dist
------------
On this file you'll have to fill in with your client details, like Client ID, Shared secret and API/Widget version.
You also need to rename this file to settings.php

unlogged.php
------------
Common workflow for unlogged users (visitors)

widget.php
----------
This example demonstrates how to use widgets