<?php

namespace Xframe\View;

/**
 * This abstract class specifies the requirements for a template view.
 */
abstract class TemplateView extends View
{
    protected $template;
    protected $viewDirectory;
    private $viewExtension;

    /**
     * Initialise the template view.
     *
     * @param string $root
     * @param string $viewExtension
     * @param string $template
     */
    public function __construct($root, $viewExtension, $template)
    {
        parent::__construct();

        $this->viewDirectory = $root . 'view' . DIRECTORY_SEPARATOR;
        $this->viewExtension = $viewExtension;

        $this->setTemplate($template);
    }

    /**
     * Set the view template file.
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $this->viewDirectory . $template . $this->viewExtension;
    }
}
