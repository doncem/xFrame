<?php

namespace Xframe\Request\Annotation;

use Annotation;

/**
 * @package request/annotation
 */
class Parameter extends Annotation
{
    public $name;
    public $validator;
    public $required = false;
    public $default;
}
