<?php

namespace Xframe\Registry;

/**
 * @property string $DEFAULT_VIEW
 * @property bool   $AUTO_REBUILD
 */
final class RequestRegistry extends AbstractRegistry
{
    const DEFAULT_VIEW = '\\Xframe\\View\\TwigView';
    const AUTO_REBUILD = true;
}
