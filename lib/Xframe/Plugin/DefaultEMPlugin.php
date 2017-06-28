<?php

namespace Xframe\Plugin;

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
        $cache = $this->dic->doctrineCache;
        $config = new Configuration();
        $config->setMetadataCacheImpl($cache);
        $driver = $config->newDefaultAnnotationDriver([$this->dic->root . 'src']);
        $config->setMetadataDriverImpl($driver);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($this->dic->tmp . DIRECTORY_SEPARATOR);
        $config->setProxyNamespace('Project\Proxies');

        $rebuild = $this->dic->registry->doctrine2->AUTO_REBUILD_PROXIES;
        $config->setAutoGenerateProxyClasses($rebuild);

        $connectionOptions = ['pdo' => $this->dic->database];

        return EntityManager::create($connectionOptions, $config, $this->dic->evm);
    }
}
