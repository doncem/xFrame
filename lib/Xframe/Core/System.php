<?php

namespace Xframe\Core;

use Xframe\Exception\ErrorHandler;
use Xframe\Exception\ExceptionHandler;
use Xframe\Exception\ExceptionOutputter;
use Xframe\Exception\Logger;
use Xframe\Plugin\DefaultCachePlugin;
use Xframe\Plugin\DefaultDatabasePlugin;
use Xframe\Plugin\DefaultDoctrineCachePlugin;
use Xframe\Plugin\DefaultDoctrineMigrationPlugin;
use Xframe\Plugin\DefaultEMPlugin;
use Xframe\Plugin\DefaultEvMPlugin;
use Xframe\Plugin\DefaultPluginContainerPlugin;
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
            'config' => $config,
            'isDev' => 'dev' === $config,
            'isLive' => 'live' === $config,
            'isTest' => 'test' === $config
        ]);

        $configFilename = 'config' . DIRECTORY_SEPARATOR . $config . '.ini';

        $this->setDefaultDatabase();
        $this->setDefaultDoctrine();
        $this->setDefaultErrorHandler();
        $this->setDefaultExceptionHandler();
        $this->setDefaultFrontController();
        $this->setDefaultRegistry($configFilename);
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

        if (\class_exists($this->registry->cache->CACHE_CLASS)) {
            $this->getDefaultCache();
        } else {
            $this->registry->cache->ENABLED = false;
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
     */
    private function setDefaultRegistry(string $filename)
    {
        $this->add('registry', function (DependencyInjectionContainer $dic) use ($filename) {
            return Registry::load($filename, $dic->root);
        });
    }

    /**
     * Set the lambda for database.
     */
    private function setDefaultDatabase()
    {
        $this->add('database', function (DependencyInjectionContainer $dic) {
            return (new DefaultDatabasePlugin($dic))->init();
        });
    }

    /**
     * Sets up the project plugins with access to the dic.
     */
    private function setDefaultPluginContainer()
    {
        $this->add('plugin', function (DependencyInjectionContainer $dic) {
            return (new DefaultPluginContainerPlugin($dic))->init();
        });
    }

    /**
     * Set the lambda function for the memcache.
     */
    private function getDefaultCache()
    {
        $this->add('cache', function (DependencyInjectionContainer $dic) {
            return (new DefaultCachePlugin($dic))->init();
        });
    }

    /**
     * Set up doctrine.
     */
    private function setDefaultDoctrine()
    {
        $this->add('doctrineCache', function (DependencyInjectionContainer $dic) {
            return (new DefaultDoctrineCachePlugin($dic))->init();
        });

        $this->add('evm', function (DependencyInjectionContainer $dic) {
            return (new DefaultEvMPlugin($dic))->init();
        });

        $this->add('em', function (DependencyInjectionContainer $dic) {
            return (new DefaultEMPlugin($dic))->init();
        });

        $this->add('migrationCLI', function (DependencyInjectionContainer $dic) {
            return (new DefaultDoctrineMigrationPlugin($dic))->init();
        });
    }
}
