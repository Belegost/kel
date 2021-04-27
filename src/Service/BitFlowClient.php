<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 21:13
 */

namespace App\Service;

use GuzzleHttp\Client as GuzzleClient;

class BitFlowClient {
    const FRAME_1MIN = 1;
    const FRAME_5MIN = 5;
    const FRAME_15MIN = 15;
    const FRAME_30MIN = 30;
    const FRAME_1HOUR = 60;
    const FRAME_1DAY = 1440;

    const EXCHANGE_KRAKEN = 1;
    const EXCHANGE_POLONIEX = 2;
    const EXCHANGE_GDAX = 3;
    const EXCHANGE_BITFLYER = 4;
    const EXCHANGE_BITSTAMP = 5;
    const EXCHANGE_BITFINEX = 6;

    /** @var GuzzleClient */
    private $guzzleClient;

    public function __construct() {
        $this->guzzleClient = new GuzzleClient([
            'base_uri' => getenv('BITFLOW_API_HOST')
        ]);
    }

    private function query(string $uri, array $params = []) {
        try {
            $response = $this->guzzleClient->get($uri, ['query' => $params]);
            return json_decode($response->getBody(), JSON_OBJECT_AS_ARRAY) ?: [];
        } catch (\Throwable $t) {
            return [];
        }
    }

    public static function makePair(string $fSym, string $tSym = 'USD') {
        return "{$fSym}_{$tSym}";
    }

    public function fetchExchanges() {
        return $this->query('fetch/exchanges');
    }

    public function fetchPairs(int $exchange) {
        return $this->query("fetch/exchanges/pairs/{$exchange}", [
//            'connect_timeout' => 1
        ]);
    }

    public function fetchCandles(string $pair, int $frame, int $limit, int $offset = 0, int $exchange = self::EXCHANGE_BITFINEX): array {
        $response = $this->query('fetch/candles/limit', [
            'pair' => $pair,
            'frame' => $frame,
            'limit' => $limit,
            'offset' => $offset,
            'exchange_id' => $exchange,
        ]);

        if (isset($response['code']) && $response['code'] == 200) {
            return array_map(function ($cdl) {
                return [
                    'time' => (new \DateTime($cdl['t']))->getTimestamp(),
                    'open' => floatval($cdl['o']),
                    'close' => floatval($cdl['c']),
                    'high' => floatval($cdl['h']),
                    'low' => floatval($cdl['l']),
                    'volume' => floatval($cdl['v']),
                    'change' => floatval($cdl['o']) - floatval($cdl['c']),
                    'changepct' => floatval($cdl['c']) != 0 ? ((floatval($cdl['o']) - floatval($cdl['c'])) / floatval($cdl['c']) * 100) : 0,
                ];

            }, $response['data']['candles']);
        }

        return [];
    }

    public function fetchDailyCandles(string $pair, int $limit = 30, int $offset = 0) {
        return $this->fetchCandles($pair, self::FRAME_1DAY, $limit, $offset);
    }

    public function fetchHourlyCandles(string $pair, int $limit = 24, int $offset = 0) {
        return $this->fetchCandles($pair, self::FRAME_1HOUR, $limit, $offset);
    }

    public function getMultiSymbolPrices(array $fSyms, array $tSyms): array {
        $result = [];
        if (!empty($fSyms) && !empty($tSyms)) {
            foreach ($fSyms as $fs) {
                $result[$fs] = [];
                foreach ($tSyms as $ts) {
                    $result[$fs][$ts] = $this->getSingleSymbolPrice($fs, $ts);
                }
            }
        }
        return $result;
    }

    public function getMultiSymbolFullData(array $fSyms, array $tSyms): array {
        $result = [];
        if (!empty($fSyms)) {
            foreach ($fSyms as $fs) {
                $result[$fs] = [];
                foreach ($tSyms as $ts) {
                    $result[$fs][$ts] = $this->getSingleSymbolFullData($fs, $ts);
                }
            }
        }
        return $result;
    }

    public function getSingleSymbolPrice(string $fSym, string $tSym): float {
        $response = $this->fetchCandles(self::makePair($fSym, $tSym), self::FRAME_1MIN, 1);
        $response = array_shift($response);

        if (isset($response['close'])) {
            return $response['close'];
        }
        return 0;
    }

    public function getSingleSymbolFullData(string $fSym, string $tSym): array {
        $response = $this->fetchCandles(self::makePair($fSym, $tSym), self::FRAME_1MIN, 1);
        return array_shift($response);
    }

    public function getSingleSymbolPriceByTime(string $fSym, string $tSym, int $time): float {
        $nowTime = new \DateTime();
        $targetTime = (new \DateTime())->setTimestamp($time);
        $mns = $nowTime->diff($targetTime)->i;

        $response = $this->fetchCandles(self::makePair($fSym, $tSym), self::FRAME_1MIN, 1, $mns);
        $response = array_shift($response);

        if (isset($response['close'])) {
            return $response['close'];
        }
        return 0;
    }
}