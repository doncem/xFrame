<?php

namespace Xframe\Plugin;

use PDO;
use Xframe\Container;
use Xframe\Registry\DatabaseRegistry;

/**
 * @package plugin
 */
class DefaultDatabasePlugin extends AbstractPlugin
{
    /**
     * @param DatabaseRegistry $registry
     *
     * @return string
     */
    private function chooseDsn(Container $registry)
    {
        switch ($registry->ENGINE) {
            case 'memory':
                $dsn = 'sqlite::memory:';

                break;
            default:
                $dsn = $registry->ENGINE . ':host=' . $registry->HOST . ';dbname=' . $registry->NAME . ($registry->PORT ? ';port=' . $registry->PORT : '');

                break;
        }

        return $dsn;
    }

    /**
     * @return PDO
     */
    public function init()
    {
        $registry = $this->dic->registry->database;
        $user = $registry->USERNAME;
        $pass = $registry->PASSWORD;

        $database = new PDO($this->chooseDsn($registry), $user, $pass);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $database;
    }
}
