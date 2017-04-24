<?php

namespace Xframe\Autoloader;

/**
 * This Autoloader uses the class name or namespace of the given class to
 * locate it, this means you can use the PEAR naming convention or you can use
 * your namespace. For instance:
 * ```
 * Xframe\Core\Autoloader = Xframe/Core/Autoloader.php
 * --- or ---
 * Xframe_Core_Autoloader = Xframe/Core/Autoloader.php
 * ```.
 *
 * @package autoloader
 */
class Autoloader
{
    /**
     * Paths to be added to the include path.
     *
     * @var array
     */
    private $paths;

    /**
     * Filename extension of class files.
     *
     * @var string
     */
    private $classExtension;

    /**
     * Constructs the Autoloader and sets the initial state.
     *
     * @param string $root
     * @param string $classExtension
     */
    public function __construct($root, $classExtension = '.php')
    {
        $this->paths = [];
        $this->paths[] = $root . 'src';
        $this->paths[] = $root . 'lib';
        $this->paths[] = $root . 'test';
        $this->paths[] = __DIR__ . '/../../';

        $this->classExtension = $classExtension;
    }

    /**
     * Add an include path to the autoloader.
     *
     * @param string $path
     */
    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    /**
     * Registers the name based autoloader with the SPL autoloader method and
     * adds the src, lib and test directories to the include path.
     */
    public function register()
    {
        $includePaths = \get_include_path();

        foreach ($this->paths as $path) {
            $includePaths .= PATH_SEPARATOR . $path;
        }

        \set_include_path($includePaths);
        \spl_autoload_register([$this, 'loader']);
    }

    /**
     * Uses the class name to locate the file by converting _ or namespace \
     * characters in the name to the system directory separator.
     *
     * @param string $class
     */
    public function loader($class)
    {
        $filename = \str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $class);

        include $filename . $this->classExtension;
    }
}
