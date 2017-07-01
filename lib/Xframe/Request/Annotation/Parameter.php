<?php

namespace Xframe\Request\Annotation;

use Xframe\Validation\Validator;

/**
 * @package request/annotation
 */
class Parameter
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Validator
     */
    public $validator;

    /**
     * @var bool
     */
    public $required = false;

    /**
     * @var mixed
     */
    public $default;

    public function __construct(string $name,
                                string $validator = null,
                                bool $required = false,
                                $default = null)
    {
        $this->name = $name;
        $this->validator = $validator;
        $this->default = $default;
        $this->required = $required;
    }
}
