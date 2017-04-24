<?php

namespace Xframe\Authentication;

/**
 * @package authentication
 */
interface Authenticator
{
    /**
     * @param string $identity
     * @param string $credential
     *
     * @return Result
     */
    public function authenticate($identity, $credential);
}
