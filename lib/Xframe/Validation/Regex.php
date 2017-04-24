<?php

namespace Xframe\Validation;

/**
 * Provides regular expression validation of strings.
 *
 * @package validation
 */
class Regex implements Validator
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $flags;

    /**
     * @var offset
     */
    private $offset;

    /**
     * @param string $pattern
     * @param int    $flags
     * @param int    $offset
     */
    public function __construct($pattern, $flags = 0, $offset = 0)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
        $this->offset = $offset;
    }

    /**
     * Checks if a given value matches a regular expression pattern.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validate($value)
    {
        $result = \preg_match(
            $this->pattern,
            $value,
            $null,
            $this->flags,
            $this->offset
        );

        return (bool) $result;
    }
}
