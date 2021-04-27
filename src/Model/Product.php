<?php


namespace App\Model;


use App\Service\MRMClient;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class Product
{
    public const CACHE_TAG = 'products-data';

    protected MRMClient $MRMClient;

    protected TagAwareCacheInterface $cache;

    public function getProductsList(): ?array
    {

        return $this->getCache()->get('products-list', function (ItemInterface $item) {
            $response = $this->getMRMClient()->loadProductsList();

            if (($data = $this->parseResponse($response)) !== null) {
                $item->expiresAfter(3600);
                $item->set($data);
                $item->tag(self::CACHE_TAG);
                return $data;
            }

            $item->expiresAfter(-1);
            return null;
        });
    }

    public function getProductByTypeAndDays(string $type, int $days): array
    {
        $result = array_filter($this->getProductsList(), function ($product) use ($type, $days) {
            return $product['type']['type'] === $type && $product['period']['days'] === $days;
        });

        return count($result) > 0 ? current($result) : [];
    }

    public function getProductPeriodsList(): ?array
    {
        return $this->getCache()->get('product-periods-list', function (ItemInterface $item) {

            $response = $this->getMRMClient()->loadProductPeriodsList();
            if (($data = $this->parseResponse($response)) !== null) {
                $item->set($data);
                $item->expiresAfter(86400);
                $item->tag(self::CACHE_TAG);
                return $data;
            }
            $item->expiresAfter(-1);
            return null;
        });
    }

    public function getPeriodByDays(int $days): ?array
    {
        $result = array_filter($this->getProductPeriodsList(), function ($period) use ($days) {
            return $period['days'] == $days;
        });
        return count($result) > 0 ? current($result) : [];
    }

    public function getProductTypesList(): ?array
    {

        return $this->getCache()->get('product-types-list', function (ItemInterface $item) {
            $response = $this->getMRMClient()->loadProductTypesList();
            if (($data = $this->parseResponse($response)) !== null) {
                $item->set($data);
                $item->expiresAfter(86400);
                $item->tag(self::CACHE_TAG);
                return $data;
            }

            $item->expiresAfter(0);
            return null;
        });
    }

    public function getProductTypeByType(string $type): ?array
    {
        $result = array_filter($this->getProductTypesList(), function ($productType) use ($type) {
            return $productType['type'] === $type;
        });

        return count($result) > 0 ? current($result) : [];
    }

    public function getClientProducts(int $accountId): ?array
    {
        return $this->getCache()->get('client-products-list-' . $accountId, function (ItemInterface $item) use ($accountId) {
            $response = $this->getMRMClient()->loadClientProductList($accountId);

            if (($data = $this->parseResponse($response)) !== null) {
                $item->expiresAfter(300);
                $item->set($data);
                $item->tag(self::CACHE_TAG);
                return $data;
            }

            $item->expiresAfter(-1);
            return null;
        });
    }

    private function parseResponse(array $response): ?array
    {
        if (isset($response['status']) && $response['status'] === 'success') {
            return $response['data'];
        }
        return null;
    }

    /**
     * @param MRMClient $MRMClient
     */
    public function setMRMClient(MRMClient $MRMClient): void
    {
        $this->MRMClient = $MRMClient;
    }

    /**
     * @return MRMClient
     */
    public function getMRMClient(): MRMClient
    {
        return $this->MRMClient;
    }

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
