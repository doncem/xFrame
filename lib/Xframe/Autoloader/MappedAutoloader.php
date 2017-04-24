<?php

namespace Xframe\Autoloader;

/**
 * Provides two autoloading strategies:
 *   1) Class map based load where a class map is generated and used to locate classes
 *   2) PEAR naming convention loader, similar to the Zend framework
 * Ideally these would be two classes, but who wants to include two files to boot the autoloader.
 * You can register either or both autoloading strategies.
 *
 * @package autoloader
 */
class MappedAutoloader
{
    /**
     * Root of the application, used by the autoloader to find files.
     *
     * @var string
     */
    private $root;

    /**
     * Contains the mapping of any classes.
     *
     * @var array
     */
    private $classMap;

    /**
     * Filename of the classmap file.
     *
     * @var string
     */
    private $classMapFile;

    /**
     * Constructs the Autoloader and sets the initial state.
     *
     * @param string $root
     * @param string $classExtension
     * @param array  $classMap
     */
    public function __construct($root, array $classMap = [])
    {
        $this->root = $root;
        $this->classMap = $classMap;
        $this->classMapFile = $root . 'tmp' . DIRECTORY_SEPARATOR . 'class-map.php';
    }

    /**
     * Registers both the class map loader and the name based loader.
     */
    public function register()
    {
        //try to load the class map
        if (!$this->loadClassMap($this->classMapFile)) {
            //if we failed, rebuild and try again
            $this->rebuildClassMap($this->classMapFile);
            $this->loadClassMap($this->classMapFile);
        }

        \spl_autoload_register([$this, 'loader']);
    }

    /**
     * Uses the class map based loading.
     *
     * @param string $class
     */
    public function loader($class)
    {
        if (\array_key_exists($class, $this->classMap)) {
            @include_once $this->classMap[$class];
        } else {
            $this->rebuildClassMap($this->classMapFile);
            $this->loadClassMap($this->classMapFile);

            if (\array_key_exists($class, $this->classMap)) {
                @include_once $this->classMap[$class];
            }
        }
    }

    /**
     * Loads the given filename and merges the classes with the current class map.
     *
     * @param string $filename
     *
     * @return bool returns true on success or false on failure
     */
    public function loadClassMap($filename)
    {
        $classes = @include $filename;

        if (\is_array($classes)) {
            $this->classMap = \array_merge($this->classMap, $classes);
        }

        return \is_array($classes);
    }

    /**
     * Create a ClassMapBuilder to regenerate the class map.
     *
     * @param string $filename
     */
    private function rebuildClassMap($filename)
    {
        //require the builder but don't use the autoloader just in case
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'ClassMapBuilder.php';

        $builder = new ClassMapBuilder($this->root);
        $builder->build();
        $builder->output($filename);
    }
}
