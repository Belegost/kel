<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 21:13
 */

namespace App\Service;

//use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
//use Symfony\Component\;
use App\ServiceAwareTrait;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
//use Symfony\Component\Cache\Simple\FilesystemCache;

class CryptCompare {
    use ServiceAwareTrait;

    /** @var Client */
    private $guzzleClient;
    private $cache;

    public function __construct() {
        $this->cache = new FilesystemAdapter();
        $this->guzzleClient = new Client([
            'base_uri' => 'https://min-api.cryptocompare.com/data/'
        ]);
    }

    private function query(string $uri, array $params = []) {
        try {
            $params = array_merge(['e' => 'CCCAGG'], $params);
            $response = $this->guzzleClient->get($uri, ['query' => $params]);

            return json_decode($response->getBody(), JSON_OBJECT_AS_ARRAY) ?: [];
        } catch (\Throwable $t) {
            return ['Response' => 'Error', 'Message' => $t->getMessage()];
        }
    }

    public function getSingleSymbolPrice(string $fSym, string $tSyms = 'USD') {
        return $this->query('price', [
            'fsym' => $fSym,
            'tsyms' => $tSyms
        ]);
    }

    public function fetchDailyCandles(string $fSym, string $tSym = 'USD', int $limit = 30, int $aggregate = 1) {
        return $this->query('histoday', [
            'fsym' => $fSym,
            'tsym' => $tSym,
            'aggregate' => $aggregate,
            'limit' => $limit
        ]);
    }

    public function getSingleSymbolPriceByTS(string $fSym, string $tSym = 'USD', int $ts) {
        return $this->query('pricehistorical', [
            'fsym' => $fSym,
            'tsyms' => $tSym,
            'ts' => $ts
        ])[$fSym];
    }

    public function getPriceHistoricalByTS(string $fSym, array $tSymsList, int $ts): array {
        $key = $this->getCacheKey([$fSym, implode('', $tSymsList), (new \DateTime())->setTimestamp($ts)->format('Ymd')]);
        $result = $this->cache->get($key);

        if (!is_array($result)) {
            $result = [];
            foreach ($this->implodeSyms($tSymsList) as $tSyms) {
                $response = $this->query('pricehistorical', [
                    'fsym' => $fSym,
                    'tsyms' => $tSyms,
                    'ts' => $ts
                ]);

                if (!isset($result[$fSym])) {
                    $result[$fSym] = isset($response[$fSym]) ? $response[$fSym] : 0;
                } else {
                    $result[$fSym] = array_merge($result[$fSym], $response[$fSym]);
                }
            }

            $this->cache->set($key, $result, 3600 * 24);
        }

        return isset($result[$fSym]) ? $result[$fSym] : [];
    }

    public function getFullSingleSymbolPriceRawInfo(string $fSym, string $tSyms = 'USD') {
        return $this->query('pricemultifull', [
            'fsyms' => $fSym,
            'tsyms' => $tSyms
        ])['RAW'][$fSym];
    }

    public function getMultiSymbolsPrice(string $fSyms, string $tSyms = 'USD') {
        return $this->query('pricemulti', [
            'fsyms' => $fSyms,
            'tsyms' => $tSyms
        ]);
    }

    public function getCoinList($invalidate = false) {
        $key = self::makeRedisKey('full-coin-list');
        $data = self::fromRedisValue($this->getRedis()->get($key), []);

        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('all/coinlist');

            if ($data['Response'] != 'Error') {
                $this->getRedis()->set($key, self::toRedisValue($data));
            }
        }

        return $data;
    }

    public function getMultipleSymbolFullData(string $fSyms, string $tSyms, $invalidate = false) {
        $key = self::makeRedisKey('multiple-symbol-full-data', $fSyms, $tSyms);
        $data = self::fromRedisValue($this->getRedis()->get($key), []);

        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('pricemultifull', [
                'fsyms' => $fSyms,
                'tsyms' => $tSyms
            ]);

            if (isset($data['Response']) && $data['Response'] == 'Error') {
                $data = [];
            }

            $this->getRedis()->setex($key, 120, self::toRedisValue($data));
        }

        return $data;
    }

    public function getHistoricalHourlyOHLCV(string $fSym, string $tSym, int $limit = 24, $invalidate = false): array {
        $key = self::makeRedisKey('historical-hourly-ohlcv', $fSym, $tSym, $limit);
        $data = self::fromRedisValue($this->getRedis()->get($key), []);

        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('histohour', [
                'fsym' => $fSym,
                'tsym' => $tSym,
                'limit' => $limit
            ]);

            if ($data['Response'] == 'Success') {
                $data = $data['Data'];
                $this->getRedis()->setex($key, 3600, self::toRedisValue($data));
            }
        }

        return $data;
    }

    public function getHistoricalDailyOHLCV(string $fSym, string $tSym, int $limit = 30, $invalidate = false): array {
        $key = self::makeRedisKey('historical-daily-ohlcv', $fSym, $tSym, $limit);
        $data = self::fromRedisValue($this->getRedis()->get($key), []);

        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('histoday', [
                'fsym' => $fSym,
                'tsym' => $tSym,
                'limit' => $limit
            ]);

            if ($data['Response'] == 'Success') {
                $data = $data['Data'];
                $this->getRedis()->setex($key, 3600, self::toRedisValue($data));
            }
        }
        return $data;
    }

    public function getHistoricalDayOHLCVTimestamp(string $fSym, string $tSyms, int $ts, $invalidate = false): array {
        $key = self::makeRedisKey('historical-day-ohlcv-timestamp', $fSym, $tSyms, $ts);
        $data = self::fromRedisValue($this->getRedis()->get($key), []);


        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('pricehistorical', [
                'fsym' => $fSym,
                'tsyms' => $tSyms,
                'ts' => $ts
            ]);

            $data = isset($data[$fSym]) ? $data[$fSym] : [];
            $this->getRedis()->setex($key, 3600, self::toRedisValue($data));
        }

        return $data;
    }

    public function getHistoricalMinuteOHLCV(string $fSym, string $tSym, int $limit, $invalidate = false): array {
        $key = self::makeRedisKey('historical-minute-ohlcv', $fSym, $tSym, $limit);
        $data = self::fromRedisValue($this->getRedis()->get($key), []);

        if (!is_array($data) || empty($data) || $invalidate) {
            $data = $this->query('histominute', [
                'fsym' => $fSym,
                'tsym' => $tSym,
                'limit' => $limit
            ]);

            if ($data['Response'] == 'Success') {
                $data = $data['Data'];
                $this->getRedis()->setex($key, 3600, self::toRedisValue($data));
            }
        }

        return $data;
    }


    private function implodeSyms(array $syms) {
        $groupSyms = [];
        $strSyms = '';
        for ($i = 0, $j = 0; $i < count($syms); $i++) {
            if ((strlen($strSyms . $syms[$i] . ',') <= 30)) {
                $strSyms .= $syms[$i] . ',';
                $groupSyms[$j] = $strSyms;
            } else {
                $strSyms = $syms[$i] . ',';
                ++$j;
            }
        }

        return array_map(function ($item) {
            return trim($item, ' ,');
        }, $groupSyms);
    }

    private function getCacheKey(array $parts = []) {
        $parts = array_merge([
            __CLASS__
        ], $parts);
        return md5(implode(':', $parts));
    }

    protected static function makeRedisKey(string ...$parts) {
        $parts = array_merge([__CLASS__], $parts);
        return md5(var_export($parts, true));
    }

    protected static function toRedisValue($value) {
        return serialize($value);
    }

    protected static function fromRedisValue($value, $default = null) {
        return !is_null($value) ? unserialize($value) : $default;
    }
}