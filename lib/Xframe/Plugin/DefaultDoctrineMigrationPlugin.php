<?php

namespace Xframe\Plugin;

use Doctrine\DBAL\Migrations\Provider\OrmSchemaProvider;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * @package plugin
 */
class DefaultDoctrineMigrationPlugin extends AbstractPlugin
{
    /**
     * @return Application
     */
    public function init()
    {
        $helperSet = new HelperSet([
            'db' => new ConnectionHelper($this->dic->em->getConnection()),
            'dialog' => new QuestionHelper(),
        ]);

        // replace the ConsoleRunner::run() statement with:
        $cli = new Application('Doctrine Migration Interface', Version::VERSION);

        $cli->setCatchExceptions(true);

        $cli->setHelperSet($helperSet);

        // Register All Doctrine Commands
        ConsoleRunner::addCommands($cli);

        // Register your own command
        $cli->addCommands([
            new DiffCommand(new OrmSchemaProvider($this->dic->em))
        ]);

        return $cli;
    }
}
