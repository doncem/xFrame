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
        $paths = [realpath($this->dic->root . 'src')];
        $isDevMode = $this->dic->isDev;

        $reader = new AnnotationReader();
        $driver = new AnnotationDriver($reader, $paths);

        $cache = $this->dic->doctrineCache;

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $config->setAutoGenerateProxyClasses($this->dic->registry->doctrine2->AUTO_REBUILD_PROXIES);
        $config->setMetadataCacheImpl($cache);
        $config->setMetadataDriverImpl($driver);
        $config->setProxyDir($this->dic->tmp . DIRECTORY_SEPARATOR);
        $config->setProxyNamespace('Project\Proxies');
        $config->setQueryCacheImpl($cache);

        $conn = ['pdo' => $this->dic->database];

        return EntityManager::create($conn, $config, $this->dic->evm);
    }
}
