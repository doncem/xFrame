<?php

namespace Xframe\Plugin;

use Xframe\Core\DependencyInjectionContainer;

/**
 * @package plugin
 */
class DefaultPluginContainerPlugin extends AbstractPlugin
{
    public function init()
    {
        $container = new DependencyInjectionContainer();

        foreach ($this->dic->registry->plugin as $key => $plugin) {
            $container->add($key, function (DependencyInjectionContainer $dic) use ($plugin) {
                $p = new $plugin($this->dic);

                return $p->init();
            });
        }

        return $container;
    }
}
