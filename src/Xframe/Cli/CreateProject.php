<?php

namespace Xframe\Cli;

use Xframe\Request\Controller;

/**
 * Endpoint for the xFrame CLI, creates a new xFrame project structure.
 */
class CreateProject extends Controller
{
    /**
     * @Request create-project
     */
    public function run()
    {
        $path = \rtrim(\getcwd() . DIRECTORY_SEPARATOR, '/\\');
        $this->view->destination = $path;

        $vendorPath = $this->dic->root . 'vendor' . DIRECTORY_SEPARATOR . 'linusnorton' . DIRECTORY_SEPARATOR . 'xframe' . DIRECTORY_SEPARATOR;

        // copy the web source folders
        $this->recursiveCopy($vendorPath . 'src', $path);
        $this->recursiveCopy($vendorPath . 'view', $path);
        $this->recursiveCopy($vendorPath . 'www', $path);

        // rebuild tmp directory
        $this->recursiveDelete($path . 'src' . DIRECTORY_SEPARATOR . 'Xframe');
        $this->recursiveDelete($path . 'tmp');

        // remove view files
        \unlink($path . 'view' . DIRECTORY_SEPARATOR . 'cli-index.twig');
        \unlink($path . 'view' . DIRECTORY_SEPARATOR . 'create-project.twig');

        // rebuild directory structure
        \mkdir($path . 'tmp');
        \chmod($path . 'tmp', 0777);

        // hack the index.php
        $this->hackIndex($path . 'www' . DIRECTORY_SEPARATOR . 'index.php');
    }

    /**
     * Reset the root path in the given file.
     *
     * @param string $filename
     */
    private function hackIndex($filename)
    {
        $content = \file_get_contents($filename);
        $content = \preg_replace('^$loader->.+;$', '', $content);
        $content = \str_replace('$loader = ', '', $content);

        \file_put_contents($filename, $content);
    }

    /**
     * Taken from somewhere on http://php.net.
     *
     * @param string $source
     * @param string $dest
     * @param array  $options
     */
    private function recursiveCopy($source,
                                   $dest,
                                   $options = [
                                       'folderPermission' => 0755,
                                       'filePermission' => 0755
                                   ])
    {
        $result = false;

        if (\is_file($source)) {
            if (DIRECTORY_SEPARATOR === $dest[\mb_strlen($dest) - 1]) {
                if (!\file_exists($dest)) {
                    cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
                }

                $__dest = $dest . DIRECTORY_SEPARATOR . \basename($source);
            } else {
                $__dest = $dest;
            }

            $result = \copy($source, $__dest);
            \chmod($__dest, $options['filePermission']);
        } elseif (\is_dir($source)) {
            if ('/' === $dest[\mb_strlen($dest) - 1]) {
                if (DIRECTORY_SEPARATOR === $source[\mb_strlen($source) - 1]) {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest = $dest . \basename($source);
                    @\mkdir($dest);
                    \chmod($dest, $options['filePermission']);
                }
            } else {
                if (DIRECTORY_SEPARATOR === $source[\mb_strlen($source) - 1]) {
                    //Copy parent directory with new name and all its content
                    @\mkdir($dest, $options['folderPermission']);
                    \chmod($dest, $options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @\mkdir($dest, $options['folderPermission']);
                    \chmod($dest, $options['filePermission']);
                }
            }

            $dirHandle = \opendir($source);

            while ($file = \readdir($dirHandle)) {
                if ('.' !== $file && '..' !== $file) {
                    if (!\is_dir($source . DIRECTORY_SEPARATOR . $file)) {
                        $__dest = $dest . DIRECTORY_SEPARATOR . $file;
                    } else {
                        $__dest = $dest . DIRECTORY_SEPARATOR . $file;
                    }

                    $result = $this->recursiveCopy($source . DIRECTORY_SEPARATOR . $file, $__dest, $options);
                }
            }

            \closedir($dirHandle);
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * Recursively delete a directory.
     *
     * @param string $dir
     */
    private function recursiveDelete($dir)
    {
        if (\is_dir($dir)) {
            $objects = \scandir($dir);

            foreach ($objects as $object) {
                if ('.' !== $object && '..' !== $object) {
                    if ('dir' === \filetype($dir . DIRECTORY_SEPARATOR . $object)) {
                        $this->recursiveDelete($dir . DIRECTORY_SEPARATOR . $object);
                    } else {
                        \unlink($dir . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }

            \reset($objects);
            \rmdir($dir);
        }
    }
}
