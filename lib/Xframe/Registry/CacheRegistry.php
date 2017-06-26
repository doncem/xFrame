<?php

namespace Xframe\Registry;

/**
 * @property string $CACHE_CLASS
 * @property bool   $ENABLED
 * @property string $HOST
 * @property int    $PORT
 *
 * @package registry
 */
final class CacheRegistry extends AbstractRegistry
{
    const CACHE_CLASS = '\\Memcahced';
    const ENABLED = false;
    const HOST = 'localhost';
    const PORT = 11211;
}
