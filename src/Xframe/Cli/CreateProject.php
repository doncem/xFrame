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
     * @Parameter -> ["path"]
     */
    public function run()
    {
        $path = $this->request->path;

        if ('.' !== $path{0} && '/' !== $path{0}) {
            $path = \getcwd() . DIRECTORY_SEPARATOR . $path;
        }

        $path = \rtrim($path, '/\\');
        $this->view->destination = $path;

        // copy the entire folder
        $this->recursiveCopy($this->dic->root, $path);

        // clean directories
        $this->recursiveDelete($path . '/src/Xframe');
        $this->recursiveDelete($path . '/lib');
        $this->recursiveDelete($path . '/tmp');
        $this->recursiveDelete($path . '/docs');

        // remove view files
        \unlink($path . '/view/cli-index.twig');

        // rebuild directory structure
        \mkdir($path . '/tmp');
        \chmod($path . '/tmp', 0777);

        // hack the index.php
        $this->resetRoot($path . '/www/index.php');
        $this->resetRoot($path . '/script/xframe.php');
        $this->resetRoot($path . '/test/bootstrap.php');
    }

    /**
     * Reset the root path in the given file.
     *
     * @param string $filename
     */
    private function resetRoot($filename)
    {
        $content = \file_get_contents($filename);
        $content = \str_replace('$root.\'', '\'xframe/', $content);

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
            if ('/' === $dest[\mb_strlen($dest) - 1]) {
                if (!\file_exists($dest)) {
                    cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
                }

                $__dest = $dest . '/' . \basename($source);
            } else {
                $__dest = $dest;
            }

            $result = \copy($source, $__dest);
            \chmod($__dest, $options['filePermission']);
        } elseif (\is_dir($source)) {
            if ('/' === $dest[\mb_strlen($dest) - 1]) {
                if ('/' === $source[\mb_strlen($source) - 1]) {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest = $dest . \basename($source);
                    @\mkdir($dest);
                    \chmod($dest, $options['filePermission']);
                }
            } else {
                if ('/' === $source[\mb_strlen($source) - 1]) {
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
                    if (!\is_dir($source . '/' . $file)) {
                        $__dest = $dest . '/' . $file;
                    } else {
                        $__dest = $dest . '/' . $file;
                    }

                    $result = $this->recursiveCopy($source . '/' . $file, $__dest, $options);
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
                    if ('dir' === \filetype($dir . '/' . $object)) {
                        $this->recursiveDelete($dir . '/' . $object);
                    } else {
                        \unlink($dir . '/' . $object);
                    }
                }
            }

            \reset($objects);
            \rmdir($dir);
        }
    }
}
