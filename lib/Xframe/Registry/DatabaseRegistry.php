<?php

namespace Xframe\Registry;

/**
 * @property string $USERNAME
 * @property string $PASSWORD
 * @property string $HOST
 * @property int    $PORT
 * @property string $PREFIX
 * @property string $NAME
 * @property string $ENGINE
 *
 * @package registry
 */
final class DatabaseRegistry extends AbstractRegistry
{
    const USERNAME = 'username';

    const PASSWORD = 'password';

    const HOST = 'host';

    const PORT = 3306;

    const PREFIX = 'x';

    const NAME = 'name';

    const ENGINE = 'mysql';
}
