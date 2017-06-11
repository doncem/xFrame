<?php

namespace Xframe\Registry;

/**
 * @property bool   $ENABLED
 * @property string $HOST
 * @property int    $PORT
 *
 * @package registry
 */
final class CacheRegistry extends AbstractRegistry
{
    const ENABLED = false;
    const HOST = 'localhost';
    const PORT = 11211;
}
