<?php


namespace App\Traits;


use Symfony\Contracts\Cache\TagAwareCacheInterface;

trait CacheAwareTrait
{

    private TagAwareCacheInterface $cache;

    /**
     * @return TagAwareCacheInterface
     */
    public function getCache(): TagAwareCacheInterface
    {
        return $this->cache;
    }

    /**
     * @param TagAwareCacheInterface $cache
     */
    public function setCache(TagAwareCacheInterface $cache): void
    {
        $this->cache = $cache;
    }


}