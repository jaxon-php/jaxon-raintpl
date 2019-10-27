<?php

namespace Jaxon\RainTpl;

use Jaxon\Contracts\View as ViewContract;
use Jaxon\Utils\View\Store;

use Rain\Tpl as Renderer;

class View implements ViewContract
{
    use \Jaxon\Features\View\Namespaces;

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

        // View namespace
        $this->setCurrentNamespace($sNamespace);

        // View data
        $xRenderer = new Renderer();
        foreach($store->getViewData() as $sName => $xValue)
        {
            $xRenderer->assign($sName, $xValue);
        }

        // Renderer configuration
        $config = array(
            "tpl_dir"       => $this->sDirectory,
            "tpl_ext"       => ltrim($this->sExtension, '.'),
            "cache_dir"     => __DIR__ . '/../cache',
        );
        Renderer::configure($config);

        // Render the template
        return trim($xRenderer->draw($this->sDirectory . $sViewName, true), " \t\n");
    }
}
