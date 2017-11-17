<?php

namespace Xframe\Plugin;

/**
 * @package plugin
 */
class DefaultCachePlugin extends AbstractPlugin
{
    public function init()
    {
        if ($this->dic->registry->cache->CACHE_ENABLED) {
            $cache = new $this->dic->registry->cache->CACHE_CLASS();
            $cache->addServer(
                $this->dic->registry->cache->HOST,
                $this->dic->registry->cache->PORT
            );

            return $cache;
        }
    }
}
