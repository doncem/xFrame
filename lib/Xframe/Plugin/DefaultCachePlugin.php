<?php

namespace Xframe\Plugin;

use Memcache;

/**
 * @package plugin
 */
class DefaultCachePlugin extends AbstractPlugin
{
    public function init()
    {
        $cache = new Memcache();
        $cache->addServer(
            $this->dic->registry->cache->HOST,
            $this->dic->registry->cache->PORT
        );

        return $cache;
    }
}
