<?php

namespace Xframe\Plugin;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

/**
 * @package plugin
 */
class DefaultEMPlugin extends AbstractPlugin
{
    /**
     * @return EntityMmanager
     */
    public function init()
    {
        if (\extension_loaded('apc')) {
            $cache = new ApcCache();
        } elseif ($this->dic->registry->cache->ENABLED) {
            $cache = new MemcacheCache();
            $cache->setMemcache($this->dic->cache);
        } else {
            $cache = new ArrayCache();
        }

        $config = new Configuration();
        $config->setMetadataCacheImpl($cache);
        $driver = $config->newDefaultAnnotationDriver([
            $this->dic->root . 'src',
            $this->dic->root . 'lib'
        ]);
        $config->setMetadataDriverImpl($driver);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($this->dic->tmp . DIRECTORY_SEPARATOR);
        $config->setProxyNamespace('Project\Proxies');

        $rebuild = $this->dic->registry->doctrine2->AUTO_REBUILD_PROXIES;
        $config->setAutoGenerateProxyClasses($rebuild);

        $connectionOptions = ['pdo' => $this->dic->database];

        return EntityManager::create($connectionOptions, $config);
    }
}
