<?php

namespace Acme\Command;

use Acme\Container;
use Behat\Mink\Driver\BrowserKitDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends Command
{
    private $container = null;

    protected function configure()
    {
        $this->setName('search')
            ->setDescription('Search on google')
            ->addArgument(
                'keywords',
                InputArgument::REQUIRED,
                'What do you want to search for today?'
            )
            ->addOption(
                'driver', 'd', InputOption::VALUE_REQUIRED, 'Mink driver', 'selenium2'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keywords = $input->getArgument('keywords');

        $page = $this->search($input->getOption('driver'), $keywords);
        $title = $page->find('css', 'title');
        $resultStats = $page->find('css', '#resultStats');

        $output->writeln(sprintf('Keywords: <info>%s</info>', $keywords));
        $output->writeln(sprintf('Title: <info>%s</info>', $title->getText()));
        $output->writeln(sprintf('Stats: <info>%s</info>', $resultStats->getText()));
    }

    protected function search($driver, $keywords)
    {
        $session = $this->getSession($driver);
        $session->visit('http://google.co.uk');
        $session->getPage()->fillField('q', $keywords);
        $session->getPage()->pressButton('btnG');

        if (!$session->getDriver() instanceof BrowserKitDriver) {
            $session->wait(2000, 'document.getElementById("resultStats");');
        }

        return $session->getPage();
    }

    protected function getSession($name)
    {
        return $this->getMink()->getSession($name);
    }

    protected function getMink()
    {
        $container = $this->getContainer();

        return $container['mink'];
    }

    protected function getContainer()
    {
        return null === $this->container ? $this->container = new Container() : $this->container;
    }
}
