<?php

namespace Xframe\Request\Annotation;

/**
 * @package request/annotation
 */
class CustomParam
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $value;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
