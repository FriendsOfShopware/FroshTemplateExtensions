<?php

namespace FroshTemplateExtensions\Extensions\Thumbnail;

interface ThumbnailServiceInterface
{
    public function getThumbnail($id, $width, $height, $keepProportions, $quality);
}