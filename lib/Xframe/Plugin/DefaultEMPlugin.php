<?php

namespace Xframe\Plugin;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Xframe\Core\DependencyInjectionContainer;

/**
 * @package plugin
 */
class DefaultEMPlugin extends AbstractPlugin
{
    const CACHE_HELPER = 'cacheHelper';

    public function __construct(DependencyInjectionContainer $dic)
    {
        parent::__construct($dic);

        $this->dic->add(self::CACHE_HELPER, function ($dic) {
            return (new Helper\EmCachePluginHelper($dic))->init();
        });
    }

    /**
     * @return EntityMmanager
     */
    public function init()
    {
        $cache = $this->dic->{self::CACHE_HELPER};
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
