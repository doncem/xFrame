<?php

namespace Xframe\Registry;

/**
 * @property string $USERNAME
 * @property string $PASSWORD
 * @property string $HOST
 * @property int    $PORT
 * @property string $NAME
 * @property string $ENGINE
 */
final class DatabaseRegistry extends AbstractRegistry
{
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const HOST = 'host';
    const PORT = 3306;
    const NAME = 'name';
    const ENGINE = 'mysql';
}
