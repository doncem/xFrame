<?php

namespace Xframe\Registry;

/**
 * @property bool   $AUTO_REBUILD
 * @property string $DEFAULT_VIEW
 * @property string $NAMESPACE_PREFIX
 *
 * @package registry
 */
final class RequestRegistry extends AbstractRegistry
{
    const DEFAULT_VIEW = '\\Xframe\\View\\TwigView';
    const AUTO_REBUILD = true;
    const NAMESPACE_PREFIX = '';
}
