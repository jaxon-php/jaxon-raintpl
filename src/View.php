<?php

namespace Jaxon\RainTpl;

use Jaxon\Sentry\Interfaces\View as ViewInterface;
use Jaxon\Sentry\View\Store;

class View implements ViewInterface
{
    /**
     * The RainTpl template renderer
     *
     * @var RainTpl
     */
    protected $xRenderer = null;

    /**
     * The template directories
     *
     * @var array
     */
    protected $aDirectories = array();

    /**
     * The view constructor
     * 
     * @return
     */
    public function __construct()
    {
    }

    /**
     * Add a namespace to this view renderer
     *
     * @param string        $sNamespace         The namespace name
     * @param string        $sDirectory         The namespace directory
     * @param string        $sExtension         The extension to append to template names
     *
     * @return void
     */
    public function addNamespace($sNamespace, $sDirectory, $sExtension = '')
    {
        $this->aDirectories[$sNamespace] = array('path' => $sDirectory, 'ext' => $sExtension);
    }

    /**
     * Render a view
     * 
     * @param Store         $store        A store populated with the view data
     * 
     * @return string        The string representation of the view
     */
    public function render(Store $store)
    {
        $sViewName = $store->getViewName();
        $sNamespace = $store->getNamespace();
        // For this view renderer, the view name doesn't need to be prepended with the namespace.
        $nNsLen = strlen($sNamespace) + 2;
        if(substr($sViewName, 0, $nNsLen) == $sNamespace . '::')
        {
            $sViewName = substr($sViewName, $nNsLen);
        }
        // View extension
        $sDirectory = '';
        $sExtension = '';
        if(key_exists($sNamespace, $this->aDirectories))
        {
            $sDirectory = rtrim($this->aDirectories[$sNamespace]['path'], '/') . '/';
            $sExtension = ltrim($this->aDirectories[$sNamespace]['ext'], '.');
        }
        // View data
        $xRenderer = new \Rain\Tpl;
        foreach($store->getViewData() as $sName => $xValue)
        {
            $xRenderer->assign($sName, $xValue);
        }
        // Renderer configuration
        $config = array(
            "tpl_dir"       => $sDirectory,
            "tpl_ext"       => ltrim($sExtension, '.'),
            "cache_dir"     => __DIR__ . '/../cache',
        );
        \Rain\Tpl::configure($config);
        error_log("Rendering template $sDirectory . $sViewName . $sExtension");
        // Render the template
        return trim($xRenderer->draw($sDirectory . $sViewName, true), " \t\n");
    }
}
