<?php

namespace Xframe\View;

/**
 * JSONView is the view for outputting json.
 *
 * @package view
 */
class JsonView extends View
{
    /**
     * Generate the JSON.
     *
     * @return string
     */
    public function execute()
    {
        return \json_encode($this->parameters);
    }
}
