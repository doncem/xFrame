<?php

namespace Xframe\Registry;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Throwable;

/**
 * Implementation of the Registry pattern. Used to store configuration.
 *
 * @package registry
 */
class Registry
{
    private $settings;

    /**
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * This method will try to locate and parse the ini file.
     * First it just looks for the filename, if that does not exist it will prepend the
     * context and try again.
     *
     * @param string $filename
     * @param string $context
     */
    public function load($filename, $context = null)
    {
        if (\is_file($filename)) {
            $file = $filename;
        } elseif (\is_file($context . DIRECTORY_SEPARATOR . $filename)) {
            $file = $context . DIRECTORY_SEPARATOR . $filename;
        } else {
            throw new FileNotFoundException(null, 0, null, $context . DIRECTORY_SEPARATOR . $filename);
        }

        try {
            $this->settings = \parse_ini_file($file);
        } catch (Throwable $e) {
            $this->settings = false;
        }

        if (false === $this->settings) {
            throw new IOException('Could not process ini file', 0, null, $file);
        }
    }

    /**
     * Get the value from the registry.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : null;
    }

    /**
     * Set the value in the registry.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->settings[$key] = $value;
    }
}
