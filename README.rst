=========================
Middle Framework skeleton
=========================

A basic (though opinionated) setup for `Middle
framework <https://github.com/jschreuder/Middle>`_. It is set up with the
following:

* `Laminas Diactoros <https://docs.laminas.dev/laminas-diactoros/>`_ as HTTP
  Message implementation, along with `Laminas HTTP handlerrunner <https://docs.laminas.dev/laminas-httphandlerrunner/>`
  for emitting responses
* `Monolog <https://seldaek.github.io/monolog/>`_ for logging
* `Pimple <http://pimple.sensiolabs.org/>`_ as the dependency injection container
* `Symfony Router <https://symfony.com/doc/current/routing.html>`_
* `phpspec <http://www.phpspec.net/>`_ for BDD style testing
* `Phinx <https://phinx.org/>`_ for database migrations
* `Symfony console <https://symfony.com/doc/current/components/console.html>`_
  for commandline commands
* JSON middleware to support requests with a JSON content-type body

------------
Installation
------------

On the commandline use composer:

.. code-block::

    $ composer create-project jschreuder/middle-skeleton myapp dev-master

After this you will have to make the logs directory writeable:

.. code-block::

    $ chmod 0755 var/logs

Next you'll setup your configuration files. Modify the database credentials in
``dev.php`` to your liking. You can change environment by renaming the
``dev.php`` file and editing the return value of ``env.php`` to return that
name.

.. code-block::

    $ mv etc/dev.php.dist etc/dev.php
    $ mv etc/env.php.dist etc/env.php

After this you should also set the correct credentials in the environment
config file if you intend to use a database.

Finally: you might want to put everything under a different namespace ;-)

----------------
Test if it works
----------------

Go to the commandline and enter ``./console middle:example MyName`` to show a
welcome message. The command is located in ``src/Command/ExampleCommand.php``.

Go to the commandline and into the ``web`` directory, enter
``php -S localhost:8080`` and go to ``http://localhost:8080`` in your browser.
It should show a JSON encoded *Hello World* message. The controller for this
is located in ``src/Controller/ExampleController.php``, the routing is set up
in ``src/GeneralRoutingProvider.php``.

Run ``bin/phpspec run`` to have phpspec run the specs on the example classes.

----------
The wiring
----------

There's a few files in which the application is wired together:

* The ``etc/app_init.php`` which loads the autoloader, sets some reasonable
  environment settings, sets up Monolog, and initiates the DiC with the
  ``GeneralServiceProvider``;
* The ``web/index.php`` which is the entry point for the web application and
  will load the routes & run the request through the application;
* The ``console`` which registers the commands;
* The DiC is configured in the ``GeneralServiceProvider`` class;
* Routing is configured in the ``GeneralRoutingProvider`` class;
* Console commands are configured in the ``ConsoleCommandsProvider`` class.

----------------------------
Included in Middle framework
----------------------------

There's a few things included in Middle framework you might want to consider
but have not been set up yet:

* **Sessions:** You can use the ``SessionMiddleware`` along with the Laminas 
  implementation to add session support to the application. Note that you will
  need to install the `Laminas Session <https://docs.laminas.dev/laminas-session/>`
  package as well.
* **Views / templates:** You can use the ``View`` and ``Renderer`` classes
  with `Twig <http://twig.sensiolabs.org/>`_ to generate output from powerful
  templates. Or implement your own view layer based on the available
  interfaces.
* Use request validators & request filters to check HTTP requests before they
  reach their controller.
