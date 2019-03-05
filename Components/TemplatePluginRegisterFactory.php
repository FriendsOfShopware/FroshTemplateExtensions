<?php

namespace FroshTemplateExtensions\Components;

use Shopware\Components\DependencyInjection\Bridge\Template;

class TemplatePluginRegisterFactory
{
    public function factory(array $factoryArgs, \IteratorAggregate $plugins)
    {
        $template = new Template();
        $smarty = $template->factory(... $factoryArgs);

        foreach ($this->getPlugins($plugins) as $plugin) {
            $smarty->registerPlugin(...$plugin);
        }

        return $smarty;
    }

    /**
     * @param AbstractSmartyExtension[] $plugins
     */
    private function getPlugins(\IteratorAggregate $plugins)
    {
        $smartyPlugins = [];

        foreach ($plugins as $plugin) {
            foreach ($plugin->getBlocks() as $name => $item) {
                $smartyPlugins[] = [\Smarty::PLUGIN_BLOCK, $name, $item];
            }

            foreach ($plugin->getCompiler() as $name => $item) {
                $smartyPlugins[] = [\Smarty::PLUGIN_COMPILER, $name, $item];
            }

            foreach ($plugin->getFunctions() as $name => $item) {
                $smartyPlugins[] = [\Smarty::PLUGIN_FUNCTION, $name, $item];
            }

            foreach ($plugin->getModifier() as $name => $item) {
                $smartyPlugins[] = [\Smarty::PLUGIN_MODIFIER, $name, $item];
            }
        }

        return $smartyPlugins;
    }
}