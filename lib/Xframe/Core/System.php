<?php

namespace Xframe\Core;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Memcache;
use PDO;
use Xframe\Exception\ErrorHandler;
use Xframe\Exception\ExceptionHandler;
use Xframe\Exception\ExceptionOutputter;
use Xframe\Exception\Logger;
use Xframe\Registry;
use Xframe\Request\FrontController;

/**
 * The System class provides access to the core resources, this includes the FrontController and Registry.
 * It also boots the application by registering the error and exception handling methods.
 *
 * @package core
 */
class System extends DependencyInjectionContainer
{
    /**
     * @param string $root
     * @param string $config
     */
    public function __construct($root, $config)
    {
        parent::__construct([
            'root' => $root,
            'tmp' => $root . 'tmp' . DIRECTORY_SEPARATOR,
            'configFilename' => 'config' . DIRECTORY_SEPARATOR . $config . '.ini',
        ]);

        $this->setDefaultDatabase();
        $this->setDefaultEm();
        $this->setDefaultErrorHandler();
        $this->setDefaultExceptionHandler();
        $this->setDefaultFrontController();
        $this->setDefaultRegistry($this->configFilename, $this->root);
        $this->setDefaultPluginContainer();
    }

    /**
     * Register the error and exception handler, load the registry.
     */
    public function boot()
    {
        $this->getErrorHandler()->register();
        $this->getExceptionHandler()->register();
        $this->getExceptionHandler()->attach(new Logger());
        $this->getExceptionHandler()->attach(new ExceptionOutputter());

        if ($this->registry->cache->ENABLED) {
            $this->getDefaultCache();
        }
    }

    /**
     * Set the lambda for errorHandler.
     */
    private function setDefaultErrorHandler()
    {
        $this->add('errorHandler', function (DependencyInjectionContainer $dic) {
            return new ErrorHandler();
        });
    }

    /**
     * Set the default ExceptionHandler.
     */
    private function setDefaultExceptionHandler()
    {
        $this->add('exceptionHandler', function (DependencyInjectionContainer $dic) {
            return new ExceptionHandler();
        });
    }

    /**
     * Set the lambda for frontController.
     */
    private function setDefaultFrontController()
    {
        $this->add('frontController', function (DependencyInjectionContainer $dic) {
            return new FrontController($dic);
        });
    }

    /**
     * Set the lambda for registry.
     *
     * @param string $filename
     * @param string $context
     */
    private function setDefaultRegistry(string $filename, string $context)
    {
        $this->add('registry', function (DependencyInjectionContainer $dic) use ($filename, $context) {
            return Registry::load($filename, $context);
        });
    }

    /**
     * Set the lambda for database.
     */
    private function setDefaultDatabase()
    {
        $this->add('database', function (DependencyInjectionContainer $dic) {
            $registry = $dic->registry->database;
            $db = $registry->ENGINE;
            $host = $registry->HOST;
            $port = $registry->PORT;
            $name = $registry->NAME;
            $user = $registry->USERNAME;
            $pass = $registry->PASSWORD;

            $database = new PDO(
                $db . ':host=' . $host . ';dbname=' . $name . ($port ? ';port=' . $port : ''),
                $user,
                $pass
            );
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $database;
        });
    }

    /**
     * Sets up the project plugins with access to the dic.
     */
    private function setDefaultPluginContainer()
    {
        $this->add('plugin', function (DependencyInjectionContainer $dic) {
            $pluginContainer = new DependencyInjectionContainer();

            foreach ($dic->registry->plugin as $key => $plugin) {
                $pluginContainer->add($key, function ($pDic) use ($dic, $plugin) {
                    $p = new $plugin($dic);

                    return $p->init();
                });
            }

            return $pluginContainer;
        });
    }

    /**
     * Set the lambda function for the memcache.
     */
    private function getDefaultCache()
    {
        $this->add('cache', function (DependencyInjectionContainer $dic) {
            $cache = new Memcache();
            $cache->addServer(
                $dic->registry->cache->HOST,
                $dic->registry->cache->PORT
            );

            return $cache;
        });
    }

    /**
     * Set up doctrine.
     */
    private function setDefaultEm()
    {
        $this->add('em', function (DependencyInjectionContainer $dic) {
            if (\extension_loaded('apc')) {
                $cache = new ApcCache();
            } elseif ($dic->registry->cache->ENABLED) {
                $cache = new MemcacheCache();
                $cache->setMemcache($dic->cache);
            } else {
                $cache = new ArrayCache();
            }

            $config = new Configuration();
            $config->setMetadataCacheImpl($cache);
            $driver = $config->newDefaultAnnotationDriver([
                $dic->root . 'src',
                $dic->root . 'lib'
            ]);
            $config->setMetadataDriverImpl($driver);
            $config->setQueryCacheImpl($cache);
            $config->setProxyDir($dic->tmp . DIRECTORY_SEPARATOR);
            $config->setProxyNamespace('Project\Proxies');

            $rebuild = $dic->registry->doctrine2->AUTO_REBUILD_PROXIES;
            $config->setAutoGenerateProxyClasses($rebuild);

            $connectionOptions = ['pdo' => $dic->getDatabase()];

            return EntityManager::create($connectionOptions, $config);
        });
    }
}
