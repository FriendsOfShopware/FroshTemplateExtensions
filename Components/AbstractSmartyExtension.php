<?php

namespace FroshTemplateExtensions\Components;

abstract class AbstractSmartyExtension
{
    public function getFunctions()
    {
        return [];
    }

    public function getBlocks()
    {
        return [];
    }

    public function getCompiler()
    {
        return [];
    }

    public function getModifier()
    {
        return [];
    }
}
