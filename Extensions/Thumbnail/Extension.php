<?php

namespace FroshTemplateExtensions\Extensions\Thumbnail;

use FroshTemplateExtensions\Components\AbstractSmartyExtension;

class Extension extends AbstractSmartyExtension
{
    /**
     * @var ThumbnailService
     */
    private $service;

    public function __construct(ThumbnailService $service)
    {
        $this->service = $service;
    }

    public function getFunctions()
    {
        return [
            'thumbnail' => [$this, 'renderThumbnail']
        ];
    }

    public function renderThumbnail(array $params)
    {
        if (!isset($params['width'])) {
            throw new \RuntimeException('Width is required');
        }

        if (!isset($params['height'])) {
            throw new \RuntimeException('Height is required');
        }

        if (!isset($params['keepProportions'])) {
            $params['keepProportions'] = true;
        }

        if (!isset($params['quality'])) {
            $params['quality'] = 90;
        }

        if (!isset($params['id']) && !isset($params['path'])) {
            throw new \RuntimeException('id or path is required');
        }

        return $this->service->getThumbnail(
            $params['id'] ? $params['id'] : $params['path'],
            $params['width'],
            $params['height'],
            $params['keepProportions'],
            $params['quality']
        );
    }
}