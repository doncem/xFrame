<?php

namespace Xframe;

use Throwable;
use Xframe\Registry\CacheRegistry;
use Xframe\Registry\DatabaseRegistry;
use Xframe\Registry\Doctrine2Registry;
use Xframe\Registry\RequestRegistry;
use Xframe\Registry\TwigRegistry;

/**
 * Implementation of the Registry pattern. Used to store configuration.
 *
 * @property RequestRegistry $request
 * @property DatabaseRegistry $database
 * @property Doctrine2Registry $doctrine2
 * @property TwigRegistry $twig
 * @property CacheRegistry $cache
 * @property Container $plugin
 *
 * @package registry
 */
class Registry extends Container
{
    private static function getFile(string $filename, string $context = null)
    {
        if (\is_file($filename)) {
            $file = $filename;
        } elseif (\is_file($context . DIRECTORY_SEPARATOR . $filename)) {
            $file = $context . DIRECTORY_SEPARATOR . $filename;
        } else {
            $file = null;
        }

        return $file;
    }

    /**
     * This method will try to locate and parse the ini file.
     * First it just looks for the filename, if that does not exist it will prepend the
     * context and try again.
     *
     * @param string $filename
     * @param string $context
     *
     * @return Registry
     */
    public static function load(string $filename, string $context = null)
    {
        $file = self::getFile($filename, $context);

        try {
            $settings = \parse_ini_file($file, true);
        } catch (Throwable $e) {
            $settings = [];
        }

        $request = $settings['request'] ?? [];
        $database = $settings['database'] ?? [];
        $doctrine = $settings['doctrine2'] ?? [];
        $twig = $settings['twig'] ?? [];
        $cache = $settings['cache'] ?? [];
        $plugin = $settings['plugin'] ?? [];

        unset(
            $settings['request'],
            $settings['database'],
            $settings['doctrine2'],
            $settings['twig'],
            $settings['cache'],
            $settings['plugin']
        );

        return new self(\array_merge([
            'request' => new RequestRegistry($request),
            'database' => new DatabaseRegistry($database),
            'doctrine2' => new Doctrine2Registry($doctrine),
            'twig' => new TwigRegistry($twig),
            'cache' => new CacheRegistry($cache),
            'plugin' => new Container($plugin)
        ], \array_map(function($item) {
            return new Container($item);
        }, $settings)));
    }
}
