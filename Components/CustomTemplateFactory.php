<?php

namespace FroshTemplateExtensions\Components;

use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CustomTemplateFactory implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('template_factory');
        $definition->setClass(TemplatePluginRegisterFactory::class);

        $template = $container->getDefinition('template');
        $template->setArguments([$template->getArguments(), new TaggedIteratorArgument('frosh.smarty_plugin')]);
    }
}