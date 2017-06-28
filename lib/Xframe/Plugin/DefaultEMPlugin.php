<?php

namespace Xframe\Plugin;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Xframe\Core\DependencyInjectionContainer;

/**
 * @package plugin
 */
class DefaultEMPlugin extends AbstractPlugin
{
    const CACHE_HELPER = 'cacheHelper';
    const TABLE_PREFIX = 'tablePrefix';

    public function __construct(DependencyInjectionContainer $dic)
    {
        parent::__construct($dic);

        $this->dic->add(self::CACHE_HELPER, function ($dic) {
            return (new Helper\EmCachePluginHelper($dic))->init();
        });
        $this->dic->add(self::TABLE_PREFIX, function ($dic) {
            return new Helper\EmTablePrefixPluginHelper($dic->registry->database->PREFIX . '_');
        });
    }

    /**
     * @return EventManager|null
     */
    private function getEventManager()
    {
        if (\mb_strlen($this->dic->registry->database->PREFIX) > 0) {
            $evm = new EventManager();
            $tablePrefix = $this->dic->{self::TABLE_PREFIX};
            $evm->addEventListener(Events::loadClassMetadata, $tablePrefix);
        } else {
            $evm = null;
        }

        return $evm;
    }

    /**
     * @return EntityMmanager
     */
    public function init()
    {
        $cache = $this->dic->{self::CACHE_HELPER};
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

        return EntityManager::create($connectionOptions, $config, $this->getEventManager());
    }
}
