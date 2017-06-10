<?php

namespace Xframe;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
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
            throw new FileNotFoundException(null, 0, null, $context . DIRECTORY_SEPARATOR . $filename);
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
            $settings = false;
        }

        if (false === $settings) {
            throw new IOException('Could not process ini file', 0, null, $file);
        }

        return new self([
            'request' => new RequestRegistry($settings['request'] ?? []),
            'database' => new DatabaseRegistry($settings['database'] ?? []),
            'doctrine2' => new Doctrine2Registry($settings['doctrine2'] ?? []),
            'twig' => new TwigRegistry($settings['twig'] ?? []),
            'cache' => new CacheRegistry($settings['cache'] ?? []),
            'plugin' => new Container($settings['plugin'] ?? [])
        ]);
    }
}
