<?php


namespace FroshTemplateExtensions\Extensions\Thumbnail\Caching;


use Shopware\Bundle\StoreFrontBundle\Service\MediaServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;
use Zend_Cache_Core;

class CachedStorefrontMediaService implements MediaServiceInterface
{
    /**
     * @var Zend_Cache_Core
     */
    private $cache;

    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    public function __construct(MediaServiceInterface $mediaService, Zend_Cache_Core $cache)
    {
        $this->mediaService = $mediaService;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function get($id, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getList($ids, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getProductsMedia($products, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getCover(Struct\BaseProduct $product, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getCovers($products, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function getProductMedia(Struct\BaseProduct $product, Struct\ShopContextInterface $context)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    private function cachedCall($function, array $arguments)
    {
        $id = md5($function . json_encode($arguments));

        if ($this->cache->test($id)) {
            return $this->cache->load($id, true);
        }

        $response = call_user_func_array([$this->mediaService, $function], $arguments);

        $this->cache->save($response, $id, ['Shopware_Config'], 86400);

        return $response;
    }
}