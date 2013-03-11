Web scraper demo
================

Example of [Mink](http://mink.behat.org/) + [Symfony Console](http://symfony.com/doc/current/components/console/introduction.html) + [Pimple](http://pimple.sensiolabs.org/).

Installation
------------

    curl -sS https://getcomposer.org/installer | php
    ./composer.phar install

Usage
-----

    ./console search lolcats
    ./console search lolcats --driver=selenium2
