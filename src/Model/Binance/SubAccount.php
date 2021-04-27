<?php


namespace App\Model\Binance;


use App\Entity\MRM\AssetHistory;
use App\Entity\MRM\AssetType;
use App\Traits\CacheAwareTrait;
use App\Traits\DoctrineAwareTrait;
use App\Traits\LoggerAwareTrait;
use App\Traits\MRM\MessengerAwareTrait AS MRMMessengerAwareTrait;
use Carbon\Carbon;
use Symfony\Contracts\Cache\ItemInterface;

class SubAccount
{
    use CacheAwareTrait,
        LoggerAwareTrait,
        DoctrineAwareTrait,
        MRMMessengerAwareTrait;

    private ?string $subAccountId = null;

    private ?string $apiKey = null;

    private ?string $apiSecret = null;

    /**
     * @return string
     */
    public function getSubAccountId(): ?string
    {
        return $this->subAccountId;
    }

    /**
     * @param string $subAccountId
     * @return SubAccount
     */
    public function setSubAccountId(string $subAccountId): SubAccount
    {
        $this->subAccountId = $subAccountId;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return SubAccount
     */
    public function setApiKey(string $apiKey): SubAccount
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     * @return SubAccount
     */
    public function setApiSecret(string $apiSecret): SubAccount
    {
        $this->apiSecret = $apiSecret;
        return $this;
    }

    public function getDepositAddress()
    {
        return $this->getMrmMessenger()->getDepositAddress(
            $this->getApiKey(),
            $this->getApiSecret()
        );
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getDepositHistory(): array
    {
        if ( ($subAccountId = $this->getSubAccountId()) !== null ) {
            try {
                $tag = 'sub-accounts_deposit-history';
                $key = $tag . '-' . $subAccountId;
                return $this->getCache()->get($key, function (ItemInterface $item) use ($subAccountId, $tag) {
                    $result = $this->getMrmMessenger()->getDepositHistory($subAccountId);

                    $item->expiresAfter(180);
                    $item->set($result);
                    $item->tag($tag);
                    return $result;
                });
            } catch (\Throwable $t) {
                $this->getLogger()->error("Funds Error: [{$t->getCode()}] {$t->getMessage()}");
            }
        }

        return [];
    }

    /**
     * @param bool $inUSDT
     * @return array|float|int
     */
    public function getTotalInvested(bool $inUSDT = false)
    {
        $deposits = [];
        $result = $this->getDepositHistory();
        foreach ($result AS $row ) {
            $coin = $row['coin'];
            if ( ! array_key_exists($coin, $deposits) ) {
                $deposits[$coin] = 0;
            }

            $deposits[$coin] += $inUSDT ? (float)$row['amount_usdt'] : (float)$row['amount'];
        }

        return $deposits;
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getAccountInformation(): array
    {
        if ( ($subAccountId = $this->getSubAccountId()) !== null ) {
            try {
                $tag = 'sub-account_information';
                $key = $tag . '-' . $subAccountId;
                return $this->getCache()->get($key, function (ItemInterface $item) use ($tag) {
                    $result = $this->getMrmMessenger()->getAccountInformation(
                        $this->getApiKey(), $this->getApiSecret()
                    );

                    $item->expiresAfter(180);
                    $item->set($result);
                    $item->tag($tag);
                    return $result;
                });
            } catch (\Throwable $t) {
                $this->getLogger()->error("Funds Error: [{$t->getCode()}] {$t->getMessage()}");
            }
        }

        return [];
    }

    public function getBalances(): array
    {
        try {
            $tag = 'client-balances';
            $key = $tag . '-' . $this->getSubAccountId();


            return $this->cache->get($key, function (ItemInterface $item) use ($tag) {
                $balances = $this->getMrmMessenger()->getClientBalances($this->getSubAccountId());

                $balances = $this->calculateBalancesUSDT($balances);
                $item->expiresAfter(60);
                $item->set($balances);
                $item->tag($tag);
                return $balances;
            });
        } catch (\Throwable $e) {
            $this->getLogger()->error("MRM Error[Client Balances]: [{$e->getCode()}] {$e->getMessage()}");
        }

        return [];
    }

    /**
     * @param bool $inUSDT
     * @return array|float|int|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getCurrentBalance(bool $inUSDT = false)
    {
        $balances = $this->getBalances();

        if ( $inUSDT ) {
            return ! empty($balances) ? array_sum(
                array_map(function ($balance) {
                    return $balance['totalUsdt'];
                }, $balances)
            ) : 0;
        }

        return $balances;
    }

    public function getEquityDayChange(int $days = 2)
    {
        $balances = $this->getCurrentBalance();
        $result = [];
        if ( ! empty($balances) ) {
            for ($i = 0; $i < $days; $i++) {
                $result[] = array_sum(
                    array_map(function ($balance) {
                        return $balance['totalUsdt'];
                    }, $this->calculateBalancesUSDT($balances, Carbon::now()->subDays($i)))
                );
            }
        }

        return $result;
    }

    public function getNonInvested(): float
    {
        $balances = $this->getBalances();

        return !empty($balances) ? array_sum(
            array_map(function ($balance) {
                return $balance['freeUsdt'];
            }, $balances)
        ) : 0;
    }

    public function getInvestedFunds(): float
    {
        $balances = $this->getBalances();

        return !empty($balances) ? array_sum(
            array_map(function ($balance) {
                return $balance['lockedUsdt'];
            }, $balances)
        ) : 0;
    }

    private function calculateBalancesUSDT(array $balances, \DateTime $datetime = null): array
    {
        $assets = [];
        foreach ($balances AS $code => $balance) {
            $history = $this->getDoctrine()
                ->getRepository('MRM:AssetHistory')
                ->fetchHistoricalData(
                    $code,
                    AssetHistory::TIMEFRAME_HOUR,
                    null,
                    $datetime ? $datetime->format('Y-m-d H:i:s') : null,
                    1
                );
            if ( ! empty($history) ) {
                /** @var AssetHistory $history */
                $history = current($history);
                $assets[$history->getAssetType()->getCode()] = $history;
            }
        }

        foreach ($balances AS $code => $balance) {

            /** @var AssetHistory $history */
            $history = array_key_exists($code, $assets) ? $assets[$code] : null;
            $price = $code === 'USDT' ? 1 : $history->getClose();

            $balances[$code]['total'] = (float)$balances[$code]['total'];
            $balances[$code]['free'] = (float)$balances[$code]['free'];
            $balances[$code]['locked'] = (float)$balances[$code]['locked'];
            $balances[$code]['totalUsdt'] = round($balances[$code]['total'] * $price, 2);
            $balances[$code]['freeUsdt'] = round($balances[$code]['free'] * $price, 2);
            $balances[$code]['lockedUsdt'] = round($balances[$code]['locked'] * $price, 2);
        }

        return $balances;
    }


    /**
     * @param array $data
     * @param \DateTime|null $datetime
     * @return float|int|mixed
     */
    private function convertToUSDT(array $data, \DateTime $datetime = null)
    {
        $resultCoin = 'USDT';
        $coins = array_filter(array_keys($data), function ($item) use ($resultCoin) {
            return $item !== $resultCoin;
        });

        if ( ! $datetime ) {
            $assets = $this->getDoctrine()->getRepository('MRM:AssetType')
                ->findBy([
                    'code' => $coins,
                ]);

        } else {
            $assets = [];
            foreach ($coins AS $coin) {
                $assets[] = $this->getDoctrine()->getRepository('MRM:AssetHistory')
                    ->fetchHistoricalData(
                        $coin,
                        AssetHistory::TIMEFRAME_HOUR,
                        null,
                        $datetime->format('Y-m-d H:i:s'),
                        1
                    )[0];
            }
        }

        foreach ( $assets AS $asset ) {
            $code = $asset instanceof AssetType ? $asset->getCode() : $asset->getAssetType()->getCode();
            $price = $asset instanceof AssetType ? $asset->getPrice() : $asset->getClose();
            $data[$resultCoin] += $data[$code] * $price;
        }
        return $data[$resultCoin];
    }
}
