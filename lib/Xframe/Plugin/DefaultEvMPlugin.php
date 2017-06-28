<?php

namespace Xframe\Plugin;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Events;
use Xframe\Plugin\Helper\EmTablePrefixPluginHelper;

/**
 * @package plugin
 */
class DefaultEvMPlugin extends AbstractPlugin
{
    public function init()
    {
        if (\mb_strlen($this->dic->registry->database->PREFIX) > 0) {
            $evm = new EventManager();
            $tablePrefix = new EmTablePrefixPluginHelper($this->dic->registry->database->PREFIX);

            $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);

            return $evm;
        }

        return null;
    }
}
