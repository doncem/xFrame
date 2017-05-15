<?php

namespace Xframe\View;

use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Xframe\Registry\Registry;

/**
 * TwigView is the view for Fabien Potiencier's Twig templating language.
 *
 * @package view
 */
class TwigView extends TemplateView
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * Creates the Twig objects.
     *
     * @param Registry $registry
     * @param string   $root
     * @param string   $tmpDir
     * @param string   $template
     * @param bool     $debug
     */
    public function __construct(Registry $registry,
                                $root,
                                $tmpDir,
                                $template,
                                $debug = false)
    {
        parent::__construct('', '.twig', $template);
        $this->model = [];

        $this->twig = new Twig_Environment(
            new Twig_Loader_Filesystem($root . 'view' . DIRECTORY_SEPARATOR),
            [
                'cache' => $tmpDir,
                'debug' => $debug,
                'auto_reload' => $registry->get('AUTO_REBUILD_TWIG')
            ]
        );

        if ($debug) {
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
    }

    /**
     * Use Twig to generate some HTML.
     *
     * @return string
     */
    public function execute()
    {
        $template = $this->twig->loadTemplate($this->template);

        return $template->render($this->attributes);
    }
}
