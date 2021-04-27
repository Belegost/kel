<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 12.06.2018
 * Time: 21:26
 */

namespace App\Lib;

use App\Entity\MRM\AssetHistory;
use App\Entity\MRM\AssetType;
use App\Service\BitFlowClient;
use App\Service\CryptCompare;
use Carbon\Carbon;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MCModule {
    const TIME_FRAME_MIN = 'min';
    const TIME_FRAME_HOUR = 'hour';
    const TIME_FRAME_DAY = 'day';

    private $cryptCompare;
    private $bitFlowClient;

    private ManagerRegistry $doctrine;

    private TagAwareCacheInterface $cache;

    public function __construct(CryptCompare $cryptCompare) {
        $this->cryptCompare = $cryptCompare;
        $this->bitFlowClient = new BitFlowClient();
    }

    public function setDoctrine(ManagerRegistry $doctrine): void
    {
        $this->doctrine = $doctrine;
    }

    public function setCache(TagAwareCacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * @return CryptCompare
     */
    public function getCryptCompare(): CryptCompare {
        return $this->cryptCompare;
    }

    /**
     * @return BitFlowClient
     */
    public function getBitFlowClient(): BitFlowClient {
        return $this->bitFlowClient;
    }

    public function generateProductTypesRenderData(array $products): array
    {
        $renderData = [];
        foreach ($products as &$product) {
            $renderData[$product['code']] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'type' => $product['type'],
                'period' => $product['period'],
                'days' => $product['period']['days'],
                'price' => (float)$product['price'],
                'assets' => $product['assets'],
            ];

            $period = "-{$product['period']['days']} days";
            $start_ts = Carbon::now()
                ->modify($period)
                ->startOfDay()
                ->getTimestamp();


            $cacheKey = 'history-equity-' . $product['id'] . '-' . $start_ts . '-' . self::TIME_FRAME_DAY;
            $historyEquity = $this->cache->get($cacheKey, function (ItemInterface $item) use ($product, $start_ts) {
                $historyEquity = $this->generateProductGainForPeriod($product['assets'], (float)$product['price'], $start_ts, self::TIME_FRAME_DAY);

                if ( ! empty($historyEquity) ) {
                    $item->set($historyEquity);
                    $item->expiresAfter(3600);
                    $item->tag('products-equity');

                    return $historyEquity;
                }
                $item->expiresAfter(0);

                return [];
            });


            $currentCost = end($historyEquity);
            reset($historyEquity);

            $renderData[$product['code']]['returns'] = round(($currentCost - (float)$product['price']), 2);
            $renderData[$product['code']]['direction'] = $currentCost > (float)$product['price'] ? 'up' : 'down';
            $renderData[$product['code']]['historyEquity'] = $historyEquity;
        }

        return $renderData;
    }

    public function generateClientProductsRenderData(array $products): array
    {
        $renderData = [];
        $btcAsset = $this->doctrine->getRepository('MRM:AssetType')
            ->findOneBy([
                'code' => 'BTC',
            ]);


        $priceUsd = (float)$btcAsset->getPrice();

        foreach ($products as $product) {
            $renderData[$product['id']] = [
                'status' => 'Active',
                'code' => $product['product']['code'],
                'name' => $product['product']['name'],
                'type' => $product['product']['type']['type'],
                'price' => (float)$product['price'],
                'quantity' => (float)$product['quantity'],
            ];

            $historyEquity = $this->calculateClientProductHistoryEquity($product);

            $currentPoint = end($historyEquity);
            reset($historyEquity);

            $currentEquityUsd = $currentPoint['totalValue'];
            $currentEquityBtc = $currentEquityUsd / $priceUsd;

            $renderData[$product['id']]['historyEquity'] = $historyEquity;
            $renderData[$product['id']]['currentPoint'] = $currentPoint;
            $renderData[$product['id']]['currentEquityUsd'] = $currentEquityUsd;
            $renderData[$product['id']]['currentEquityBtc'] = $currentEquityBtc;
            $renderData[$product['id']]['currentEquityPct'] = 100 + ((($currentEquityUsd - (float)$product['price']) / (float)$product['price']) * 100);



        }
        return $renderData;
    }

    public function calculateClientProductHistoryEquity(array $product): array
    {

        $result = [];

        $from = Carbon::now()->subDays($product['product']['period']['days'])->format('Y-m-d H:i:s');
        $to = Carbon::now()->format('Y-m-d H:i:s');
        /** @var AssetType $usdtAsset */
        $usdtAsset = $this->doctrine->getRepository('MRM:AssetType')->findOneBy([
            'code' => 'USDT',
        ]);

        foreach ($product['assets'] as $productAsset) {
            $historyData = $this->doctrine->getRepository('MRM:AssetHistory')
                ->fetchHistoricalData(
                    $productAsset['asset']['code'],
                    AssetHistory::TIMEFRAME_DAY,
                    $from,
                    $to
                );
            $weight = $productAsset['weight'];
            $quantity = $productAsset['quantity'];

            foreach ($historyData as $candle) {
                /** @var AssetHistory $candle */

                $timestamp = $candle->getDatetime()->getTimestamp();
                if ( ! array_key_exists($timestamp, $result) ) {
                    $result[$timestamp] = [
                        'totalValue' => 0,
                        'assets' => [],
                    ];
                }

                $result[$timestamp]['totalValue'] += round(($quantity * (float)$candle->getClose()), 2);
                $change = (float)$candle->getClose() - (float)$candle->getOpen();

                switch ( true ) {
                    case ($change !== 0 && (float)$candle->getOpen() !== 0):
                        $changePrcnt = round((($change / (float)$candle->getOpen()) * 100), 2);
                        break;
                    case ($change !== 0 && (float)$candle->getOpen() === 0):
                        $changePrcnt = 100;
                        break;
                    default:
                        $changePrcnt = 0;
                        break;
                }

                $result[$timestamp]['assets'][$candle->getAssetType()->getCode()] = [
                    'weight' => $weight,
                    'price' => (float)$candle->getClose(),
                    'value' => round(($quantity * (float)$candle->getClose()), 2),
                    'change' => $change,
                    'changepct' => $changePrcnt,
                    'pair' => $candle->getAssetType()->getCode() . '/' . $usdtAsset->getCode(),
                    'label' => $candle->getAssetType()->getLabel() . '/' . $usdtAsset->getLabel(),
                    'direction' => $change >= 0 ? 'up' : 'down',
                ];

            }
        }

        return $result;
    }

    public function calculateDebitBalanceBTC(float $balanceBTC, float $debitAmount, string $debitCurrency = 'USD'): array {
        $priceBTC = $this->getCryptCompare()->getSingleSymbolPrice('BTC', $debitCurrency)[$debitCurrency];
        $debitAmountBTC = $debitAmount / $priceBTC;

        return [
            'debitAmountBTC' => $debitAmountBTC,
            'remnantAmountBTC' => $balanceBTC - $debitAmountBTC
        ];
    }

    /**
     * @param array $assets
     * @return array
     */
    public function generateFullCoinsInfo(array $assets)
    {

        $result = [];

        foreach ($assets as $asset) {
            $code = $asset['code'];
            $result[$code] = $asset;
            $result[$code]['percent'] = 0;
            $result[$code]['chart'] = [
                'd1' => ['data' => [], 'labels' => []],
                'w1' => ['data' => [], 'labels' => []],
                'm1' => ['data' => [], 'labels' => []],
                'm3' => ['data' => [], 'labels' => []],
                'm6' => ['data' => [], 'labels' => []],
                'm12' => ['data' => [], 'labels' => []],
            ];


//            $result[$code]['ImageUrl'] = $asset->getLogo();

//            $result[$code]['value'] = round($asset->getPrice(), 2);


            $now = Carbon::now();
            $nowTS = $now->getTimestamp();
            $d1TS = Carbon::now()->modify('-24 hours')->getTimestamp();
            $m1TS = Carbon::now()->modify('-1 months')->getTimestamp();
            $m3TS = Carbon::now()->modify('-3 months')->getTimestamp();
            $m6TS = Carbon::now()->modify('-6 months')->getTimestamp();

            $candles = $this->doctrine
                ->getRepository('MRM:AssetHistory')
                ->fetchHistoricalData($code, AssetHistory::TIMEFRAME_HOUR, Carbon::now()->modify('-7 days')->format('Y-m-d H:i:s'));

            foreach ($candles as $candle) {
                /** @var AssetHistory $candle */
                $candleTS = Carbon::instance($candle->getDatetime())->getTimestamp();;
                $value = round($candle->getClose(), 2);

                if ($candleTS >= $d1TS && $candleTS <= $nowTS) {
                    $result[$code]['chart']['d1']['data'][] = $value;
                    $result[$code]['chart']['d1']['labels'][] = (new \DateTime())->setTimestamp($candleTS)->format('H:i');
                }

                $result[$code]['chart']['w1']['data'][] = $value;
                $result[$code]['chart']['w1']['labels'][] = (new \DateTime())->setTimestamp($candleTS)->format('d H:i');
            }

            $result[$code]['diff'] = (float)$result[$code]['price'] - $result[$code]['chart']['d1']['data'][0];
            $result[$code]['direct'] = $result[$code]['diff'] >= 0 ? 'up' : 'down';

            $candles = $this->doctrine
                ->getRepository('MRM:AssetHistory')
                ->fetchHistoricalData($code, AssetHistory::TIMEFRAME_DAY, Carbon::now()->modify('-1 year')->format('Y-m-d H:i:s'));;
            foreach ($candles as $candle) {
                /** @var AssetHistory $candle */
                $candleTS = Carbon::instance($candle->getDatetime())->getTimestamp();
                $value = round($candle->getClose(), 2);
                $timeLabel = (new \DateTime())->setTimestamp($candleTS)->format('Y, M.d');


                if ($candleTS >= $m1TS && $candleTS <= $nowTS) {
                    $result[$code]['chart']['m1']['data'][] = $value;
                    $result[$code]['chart']['m1']['labels'][] = $timeLabel;
                }

                if ($candleTS >= $m3TS && $candleTS <= $nowTS) {
                    $result[$code]['chart']['m3']['data'][] = $value;
                    $result[$code]['chart']['m3']['labels'][] = $timeLabel;
                }

                if ($candleTS >= $m6TS && $candleTS <= $nowTS) {
                    $result[$code]['chart']['m6']['data'][] = $value;
                    $result[$code]['chart']['m6']['labels'][] = $timeLabel;
                }

                $result[$code]['chart']['m12']['data'][] = $value;
                $result[$code]['chart']['m12']['labels'][] = $timeLabel;
            }
        }
        return $result;
    }

    public function generateAssetGainForPeriod(string $assetCode, float $amountUSD, int $startTS, string $timeFrame = self::TIME_FRAME_HOUR) {

        $prices = $this->doctrine->getRepository('MRM:AssetHistory')
            ->fetchHistoricalData(
                $assetCode,
                AssetHistory::TIMEFRAME_DAY,
                null,
                date('Y-m-d H:i:s', $startTS),
                1
            );

        $price = !empty($prices) ? $prices[0] : null;

        $value = $price instanceof AssetHistory ? ( $amountUSD / (float)$price->getOpen() ) : 0;
        $now = new \DateTime();

        switch ($timeFrame) {
            case self::TIME_FRAME_MIN:
                throw new \Exception('Not implemented yet.');
                $limit = $now->diff((clone $now)->setTimestamp($startTS))->days * 24 * 60;
                $candles = $this->getCryptCompare()->getHistoricalMinuteOHLCV($assetCode, 'USD', $limit);
                break;
            case self::TIME_FRAME_HOUR:
                $limit = (new \DateTime())->diff((new \DateTime())->setTimestamp($startTS))->days * 24;
                $candles = $this->getCryptCompare()->getHistoricalHourlyOHLCV($assetCode, 'USD', $limit);
                break;
            case self::TIME_FRAME_DAY:
            default:
                $limit = $now->diff((clone $now)->setTimestamp($startTS))->days;

                /** @var AssetType $asset */
                $asset = $this->doctrine->getRepository('MRM:AssetType')
                    ->findOneBy(['code' => $assetCode]);
                $candles = $this->doctrine->getRepository('MRM:AssetHistory')
                    ->fetchDailyHistoricalData(
                        $asset,
                        $limit,
                        AssetHistory::TIMEFRAME_DAY
                    );
        }

        $result = [];
        foreach ($candles as $candle) {
            /** @var AssetHistory $candle */
            $result[Carbon::instance($candle->getDatetime())->getTimestamp()] = $value * $candle->getClose();
        }

        return $result;
    }

    public function generateProductGainForPeriod(array $assetList, float $investAmountUSD, int $startTS, string $timeFrame = self::TIME_FRAME_HOUR): array
    {
        $result = [];

        foreach ($assetList as $code => $weight) {
            $amount = $investAmountUSD * ($weight / 100);
            $assetGainData = $this->generateAssetGainForPeriod($code, $amount, $startTS, $timeFrame);

            foreach ($assetGainData as $ts => $value) {
                if (!isset($result[$ts])) {
                    $result[$ts] = 0;
                }
                $result[$ts] += $value;
            }
        }

        return $result;
    }

    public function generateProductsPortfolioData(array $products) {
        $result = [];

        foreach ($products as $key => $product) {
            $result[$key] = [
                'pair' => "{$product['name']}/USD",
                'fullname' => "{$product['name']} / Dollar",
                'persent' => 0,
                'ImageUrl' => "",
                'chart' => [
                    'd1' => ['data' => [], 'labels' => []],
                    'w1' => ['data' => [], 'labels' => []],
                    'm1' => ['data' => [], 'labels' => []],
                    'm3' => ['data' => [], 'labels' => []],
                    'm6' => ['data' => [], 'labels' => []],
                    'm12' => ['data' => [], 'labels' => []]
                ]
            ];

            $now = new \DateTime();
            $nowTS = $now->getTimestamp();
            $d1TS = (clone $now)->modify('-24 hours')->getTimestamp();
            $m1TS = (clone $now)->modify('-1 months')->getTimestamp();
            $m3TS = (clone $now)->modify('-3 months')->getTimestamp();
            $m6TS = (clone $now)->modify('-6 months')->getTimestamp();

            $gainData1W = $this->generateProductGainForPeriod($product['assets'], floatval($product['cost_usd']), (clone $now)->modify("-7 days")->getTimestamp(), self::TIME_FRAME_HOUR);
            foreach ($gainData1W as $gainTS => $value) {
                if ($gainTS >= $d1TS && $gainTS <= $nowTS) {
                    $result[$key]['chart']['d1']['data'][] = round($value, 2);
                    $result[$key]['chart']['d1']['labels'][] = (new \DateTime())->setTimestamp($gainTS)->format('H:i');
                }

                $result[$key]['chart']['w1']['data'][] = round($value, 2);
                $result[$key]['chart']['w1']['labels'][] = (new \DateTime())->setTimestamp($gainTS)->format('d H:i');
            }

            $gainDataPeriod = $this->generateProductGainForPeriod($product['assets'], floatval($product['cost_usd']), (clone $now)->modify("-12 months")->getTimestamp(), self::TIME_FRAME_DAY);
            foreach ($gainDataPeriod as $gainTS => $value) {
                $timeLabel = (new \DateTime())->setTimestamp($gainTS)->format('Y, M.d');

                if ($gainTS >= $m1TS && $gainTS <= $nowTS) {
                    $result[$key]['chart']['m1']['data'][] = round($value, 2);
                    $result[$key]['chart']['m1']['labels'][] = $timeLabel;
                }

                if ($gainTS >= $m3TS && $gainTS <= $nowTS) {
                    $result[$key]['chart']['m3']['data'][] = round($value, 2);
                    $result[$key]['chart']['m3']['labels'][] = $timeLabel;
                }

                if ($gainTS >= $m6TS && $gainTS <= $nowTS) {
                    $result[$key]['chart']['m6']['data'][] = round($value, 2);
                    $result[$key]['chart']['m6']['labels'][] = $timeLabel;
                }

                $result[$key]['chart']['m12']['data'][] = round($value, 2);
                $result[$key]['chart']['m12']['labels'][] = $timeLabel;
            }

            $currentCost = end($gainDataPeriod);
            reset($gainDataPeriod);

            $result[$key]['diff'] = round(($currentCost - floatval($product['cost_usd'])), 2);
            $result[$key]['direct'] = $result[$key]['diff'] > 0 ? 'up' : 'down';
            $result[$key]['assets'] = $product['assets'];
            $result[$key]['priceUSD'] = $product['cost_usd'];
            $result[$key]['name'] = $product['name'];
            $result[$key]['imgUrl'] = $product['imgUrl'];
        }

        return $result;
    }

}
