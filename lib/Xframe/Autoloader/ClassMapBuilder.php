<?php

namespace Xframe\Autoloader;

/**
 * Builds the class map file for the given path.
 *
 * @package autoloader
 */
class ClassMapBuilder
{
    /**
     * The root directory.
     *
     * @var string
     */
    private $root;

    /**
     * Acceptable class file extensions.
     *
     * @var array
     */
    private $fileTypes;

    /**
     * The classes and interfaces found under the root directory.
     *
     * @var array
     */
    private $classes;

    /**
     * @param string $root
     * @param array  $fileTypes
     */
    public function __construct($root, $fileTypes = ['php'])
    {
        $this->root = $root;
        $this->fileTypes = $fileTypes;
        $this->classes = [];
    }

    /**
     * Recursively scans directories on the root directory for class files.
     */
    public function build()
    {
        $this->scanDirectory($this->root . 'lib');
        $this->scanDirectory($this->root . 'src');
        $this->scanDirectory($this->root . 'test');
    }

    /**
     * Loads the given directory and scans it for php files.
     *
     * @param string $directory
     */
    private function scanDirectory($directory)
    {
        $dh = @\opendir($directory);

        //if the directory could be opened
        if (false !== $dh) {
            //for each file
            while (($file = \readdir($dh)) !== false) {
                if ('.' === $file || '..' === $file) {
                    continue;
                }

                $fullpath = $directory . DIRECTORY_SEPARATOR . $file;
                $ext = \pathinfo($fullpath, PATHINFO_EXTENSION);

                //if the file is a directory scan the directory
                if (\is_dir($fullpath)) {
                    $this->scanDirectory($fullpath);
                } elseif (\in_array($ext, $this->fileTypes, true)) {
                    $this->scanFile($fullpath);
                }
            }
        }

        \closedir($dh);
    }

    /**
     * Scans the given file for classes and adds them to the class map.
     *
     * @param string $filename
     */
    private function scanFile($filename)
    {
        $contents = \file_get_contents($filename);
        $tokens = \token_get_all($contents);
        $classes = [];
        $filename = \realpath($filename);
        $numTokens = \count($tokens);

        for ($i = 2; $i < $numTokens; ++$i) {
            $token = $tokens[$i];

            if (\is_array($token)) {
                if (T_CLASS_C === $token[0] || T_INTERFACE === $token[0]) {
                    //get the class name
                    for ($j = $i + 1; $j < $numTokens; ++$j) {
                        if (T_STRING === $tokens[$j][0]) {
                            $name .= $tokens[$j][1];

                            break;
                        }
                    }

                    $this->classes[$name] = $filename;
                    $name = '';
                    $i = $j;
                } elseif (T_NAMESPACE === $token[0]) {
                    $name = '';

                    for ($j = $i + 1; $j < $numTokens; ++$j) {
                        if (';' === $tokens[$j]) {
                            $i = $j;

                            break;
                        } elseif (T_STRING === $tokens[$j][0]) {
                            $name .= $tokens[$j][1] . '\\';
                        }
                    }
                }
            }
        }
    }

    /**
     * Outputs the class map to the given file.
     *
     * @param string $filename
     */
    public function output($filename)
    {
        $contents = '<?php ' . PHP_EOL . '$c = [];' . PHP_EOL;

        foreach ($this->classes as $class => $path) {
            $contents .= '$c["' . $class . '"] = "' . $path . '";' . PHP_EOL;
        }

        $contents .= 'return $c;' . PHP_EOL;
        \file_put_contents($filename, $contents);
    }
}
