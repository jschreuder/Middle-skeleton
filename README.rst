=========================
Middle Framework skeleton
=========================

A basic (though opinionated) setup for Middle framework. It is set up with the
following:

* Zend Diactoros as HTTP Message implementation
* Monolog for logging
* Pimple as the dependency injection container
* Symfony Router
* phpspec for BDD style testing
* Phinx for database migrations
* Symfony console for commandline commands

------------
Installation
------------

On the commandline use composer:

.. code-block::

    $ composer create-project jschreuder:middle-skeleton myapp

After this you will have to make the logs directory writeable:

.. code-block::

    $ chmod 0666 logs

Next you'll setup your configuration files. Modify the database credentials in
``dev.php`` to your liking. You can change environment by renaming the
``dev.php`` file and editing the return value of ``env.php`` to return that
name.

.. code-block::

    $ mv config/dev.php.dist config/dev.php
    $ mv config/env.php.dist config/env.php

After this you should also set the correct credentials in the environment
config file if you intend to use a database.

Finally: you might want to put everything under a different namespace ;-)

----------------
Test if it works
----------------

Go to the commandline and enter ``./console middle:example MyName`` to show a
welcome message. The command is located in ``src/Command/ExampleCommand.php``.

Go to the commandline and into the ``public`` directory, enter
``php -S localhost:8080`` and go to ``http://localhost:8080`` in your browser.
It should show a JSON encoded *Hello World* message. The controller for this
is located in ``src/Controller/ExampleController.php``, the routing is set up
in ``src/GeneralRoutingProvider.php``.

----------
The wiring
----------

There's a few files in which the application is wired together:

* The ``app_init.php`` which loads the autoloader, sets some reasonable
  environment settings, sets up Monolog, and initiates the DiC with the
  ``GeneralServiceProvider``;
* The ``public/index.php`` which is the entry point for the web application and
  will load the routes & run the request through the application;
* The ``console`` which registers the commands;
* The DiC is configured in the ``GeneralServiceProvider`` class;
* Routing is configured in the ``GeneralRoutingProvider`` class.
