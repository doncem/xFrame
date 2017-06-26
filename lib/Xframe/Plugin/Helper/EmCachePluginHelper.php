<?php

namespace Xframe\Plugin\Helper;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Xframe\Plugin\AbstractPlugin;

/**
 * @package plugin
 */
class EmCachePluginHelper extends AbstractPlugin
{
    /**
     * @return Cache
     */
    public function init()
    {
        if (\extension_loaded('apc')) {
            $cache = new ApcCache();
        } elseif ($this->dic->registry->cache->ENABLED) {
            $class = '\\Doctrine\\Common\\Cache' . $this->dic->registry->cache->CACHE_CLASS . 'Cache';
            $cache = new $class();
            $cache->setMemcache($this->dic->cache);
        } else {
            $cache = new ArrayCache();
        }

        return $cache;
    }
}
