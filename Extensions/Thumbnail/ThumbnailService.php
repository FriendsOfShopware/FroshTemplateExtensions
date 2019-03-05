<?php


namespace FroshTemplateExtensions\Extensions\Thumbnail;

use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\MediaServiceInterface;
use Shopware\Components\Thumbnail\Generator\Basic;

class ThumbnailService
{
    /**
     * @var MediaServiceInterface
     */
    private $storeFrontMediaService;

    /**
     * @var ContextServiceInterface
     */
    private $contextService;

    /**
     * @var \Shopware\Bundle\MediaBundle\MediaServiceInterface
     */
    private $mediaService;

    /**
     * @var Basic
     */
    private $basic;

    public function __construct(
        MediaServiceInterface $storeFrontMediaService,
        ContextServiceInterface $contextService,
        \Shopware\Bundle\MediaBundle\MediaServiceInterface $mediaService,
        Basic $basic
    ) {
        $this->storeFrontMediaService = $storeFrontMediaService;
        $this->contextService = $contextService;
        $this->mediaService = $mediaService;
        $this->basic = $basic;
    }

    public function getThumbnail($id, $width, $height, $keepProportions, $quality)
    {
        if (is_int($id)) {
            $orgPath = $this->storeFrontMediaService->get($id, $this->contextService->getShopContext())->getPath();
        } else {
            $orgPath = $id;
        }

        $path = $this->calculateRequstedFile($orgPath, $width, $height, $keepProportions, $quality);

        if ($this->mediaService->has($path)) {
            return $this->mediaService->getUrl($path);
        }

        $this->basic->createThumbnail($orgPath, $path, $width, $height, $keepProportions, $quality);

        return $this->mediaService->getUrl($path);
    }

    private function calculateRequstedFile($path, $width, $height)
    {
        return str_replace('media/image/', 'media/image/' . md5(implode(func_get_args())), $path);
    }
}