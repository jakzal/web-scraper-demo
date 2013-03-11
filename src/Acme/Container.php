<?php

namespace Acme;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;

class Container extends \Pimple
{
    public function __construct()
    {
        $this->addMinkService();
    }

    protected function addMinkService()
    {
        $this['mink_session_goutte'] = function ($container) {
            return new Session(new GoutteDriver());
        };
        $this['mink_session_selenium2'] = function ($container) {
            return new Session(new Selenium2Driver('firefox'));
        };

        $this['mink'] = $this->share(function ($container) {
            return new Mink(array(
                'goutte' => $container['mink_session_goutte'],
                'selenium2' => $container['mink_session_selenium2']
            ));
        });
    }
}
