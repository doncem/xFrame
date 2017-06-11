<?php

namespace Xframe\Registry;

use Xframe\Container;

/**
 * @package registry
 */
abstract class AbstractRegistry extends Container
{
    public function __get($key)
    {
        $value = parent::__get($key);

        if (null === $value && \defined('static::' . $key)) {
            return \constant(\get_class($this) . '::' . $key);
        }

        return $value;
    }
}
