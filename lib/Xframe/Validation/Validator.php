<?php

namespace Xframe\Validation;

/**
 * The validator interface allows objects to become annotation based validators.
 *
 * @package validation
 */
interface Validator
{
    /**
     * Perform the validation of the given value.
     *
     * @param string $value
     *
     * @return bool
     */
    public function validate($value);
}
