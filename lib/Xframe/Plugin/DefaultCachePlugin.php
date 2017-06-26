<?php

namespace Xframe\Plugin;

/**
 * @package plugin
 */
class DefaultCachePlugin extends AbstractPlugin
{
    public function init()
    {
        $cache = new $this->dic->registry->cache->CACHE_CLASS();
        $cache->addServer(
            $this->dic->registry->cache->HOST,
            $this->dic->registry->cache->PORT
        );

        return $cache;
    }
}
