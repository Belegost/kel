<?php

namespace App\Service\Analytics;

use App\Entity\Integrity\Analytics\Account;
use App\Entity\Integrity\Analytics\Instrument;
use App\Exception;
use App\Service\CryptCompare;
use App\Service\BitFlowClient;

class Stats
{
    /**
     *
     */
    const ASSETS_LIMIT = null;

    /**
     * @var
     */
    public static $percentColors = [
        '#005f89',
        '#006c9b',
        '#107dac',
        '#189ad3',
        '#34aedb',
        '#71c7ec',
        '#007489',
        '#00839b',
        '#1094ac',
        '#18b5d3',
        '#34c5db',
        '#71d9ec',
        '#268900',
        '#2b9b00',
        '#3cac10',
        '#4cd318',
        '#67db34',
        '#94ec71',
        '#890087',
        '#9b009a',
        '#ac10ab',
        '#d318d1',
        '#d634db',
        '#ec71eb',
        '#89000b',
        '#9b000c',
        '#ac101d',
        '#d31827',
        '#db3448',
        '#ec717b',
        '#895200',
        '#9b5e00',
        '#ac6e10',
        '#d38918',
        '#db9334',
        '#ecbb71',
        '#fce5cd'
    ];

    /**
     * @var Account
     */
    private $analytics;

    /**
     * @var array
     */
    private $stats;

    /**
     * @var array
     */
    private $balance;

    /**
     * @param Account $analytics
     *
     * @return $this
     */
    public function init(Account $analytics)
    {
        $this->analytics = $analytics;

        return $this;
    }

    public function getHistory()
    {
        $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
        $history = $bitfinex->get_history('USD', 'exchange');
//        echo "<pre>";
//        var_dump($history);
//        echo "</pre>";
//        die();
    }

    public function getMovements()
    {
//        $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
        $bitfinex = new BitfinexV2($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
        $cryptCompare = new CryptCompare();


        $movements = $bitfinex->get_movements();
        echo "<pre>";
        var_dump($movements);
        echo "</pre>";
        die();

        $btcTotal = 0;

        foreach ($movements as $asset => $amount) {
            if ($asset == Instrument::BTC) {
                $btcTotal += $amount;
            } else {
                $priceBtc = $cryptCompare->getSingleSymbolPrice($asset, Instrument::USD)["USD"];
                $amountUsd = $cryptCompare->getSingleSymbolPrice(Instrument::BTC, Instrument::USD)["USD"] * $priceBtc * $amount;
                $btcTotal += $amountUsd;
            }
        }

        var_dump($btcTotal);
        die();
    }

    /**
     * @return array
     */
    public function getBalanceCrypto()
    {
        $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());//
        $balances = $bitfinex->get_balances();

        $balanceAvailable = [];
        foreach ($balances as $balance) {
            if ($balance["type"] == "exchange"
                && $balance["amount"] > 0) {
                $balanceAvailable[$balance["currency"]] = $balance["amount"];
            }
        }

        return $balanceAvailable;
    }

    /**
     * @return array
     */
    private function getCryptoBalanceUsd()
    {
        /** @todo merge with below; */
        $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
        $cryptCompare = new CryptCompare();
//        $ppf = new BitFlowClient();
//
        $balances = $bitfinex->get_balances();

//        var_dump($this->analytics->getAPIkey());
//        var_dump($this->analytics->getAPIsecret());
//        var_dump("balances:");
//        var_dump($balances);
//        die();
        $balanceUsd = [];
        $totalUsd = 0;

        foreach ($balances as $balance) {
            if ($balance["type"] == "exchange"
                && $balance["amount"] > 0) {
                $amountUsd = $cryptCompare->getSingleSymbolPrice(strtoupper($balance["currency"]))["USD"] * $balance["amount"];
                $balanceUsd[$balance["currency"]] = $amountUsd;

//                $totalUsd += $amountUsd;
            }
        }

//        var_dump($balanceUsd);
//        var_dump($totalUsd);
//        die();

        return $balanceUsd;
    }

    /**
     * @return array
     */
    public function getPortfolioTotal()
    {
        $totalBalanceUsd = $this->getCryptoBalanceUsd();
        $cryptCompare = new CryptCompare();

        $totalUsd = round(array_sum($totalBalanceUsd), 2);
        $totalBtc = round($totalUsd * $cryptCompare->getSingleSymbolPrice("USD", "BTC")["BTC"], 6);

//        foreach ($totalBalanceUsd as $key => $amount) {
//            $totalBtc += $amount * $cryptCompare->getSingleSymbolPrice(strtoupper($key), "BTC")["BTC"];
//            var_dump("key: ".$key);
//            var_dump("amount: ".$amount);
//            var_dump("priceBtc: ".$cryptCompare->getSingleSymbolPrice(strtoupper($key), "BTC")["BTC"]);
//            var_dump("priceUsd: ".$amount);
//            $totalUsd += $amount;
//        }

//        var_dump($totalBtc);
//        var_dump($totalUsd);
//        die();

        return [
            'total_usd' => $totalUsd,
            'total_btc' => $totalBtc,
        ];
    }

    /**
     * @return array
     */
    public function getBalance()
    {
        $totalBalanceUsd = $this->getCryptoBalanceUsd();
        $totalUsd = array_sum($totalBalanceUsd);

        $i = 0;
        $percentBalance = [];
        foreach ($totalBalanceUsd as $key => $price) {
            $percentBalance[$key] = round(($price / $totalUsd) * 100, 2);
//            $percentBalance[$key]['color'] = self::$percentColors[$i];

            $i++;
        }

        $histogram['balance'] = implode(", ", $percentBalance);

        $colors = array_slice(self::$percentColors, 0, count($percentBalance));
        $colorsString = '';

        foreach ($colors as $color) {
            $colorsString .= "'" . $color . "', ";
        }

        $labels = array_keys($percentBalance);
        $labelsString = '';

        foreach ($labels as $label) {
            $labelsString .= "'" . strtoupper($label) . "', ";
        }

        $histogram['colors'] = substr($colorsString, 0, -2);
        $histogram['labels'] = substr($labelsString, 0, -2);

        return $histogram;
    }

    private function getTotalBalance()
    {

    }

    /**
     * @return array
     */
    public function getCandles(array $namedInstruments)
    {
        $ppf = new BitFlowClient();

        $stats = $this->getStats($namedInstruments);
        $formattedCandles = [];
        $timeframes = [
            'day1' => 1,
            'w1' => 15,
            'm1' => 30,
            'm3' => 60,
            'y1' => 1440,
        ];

        $formats = [
            'day1' => 'H:i',
            'w1' => 'M d H:i',
            'm1' => 'M d H:i',
            'm3' => 'M d H:i',
            'y1' => 'Y M',
        ];

        foreach ($stats as $key => $item) {
            $pair = str_replace("/", '_', $key);

            foreach ($timeframes as $period => $timeframe) {
                $fetchCandles = $ppf->fetchCandles($pair, $timeframe, 500);
                $fetchCandlesOffset = $ppf->fetchCandles($pair, $timeframe, 500, 500);
//                var_dump($fetchCandles);
//                die();

                $candles = array_reverse(array_merge($fetchCandles, $fetchCandlesOffset));

                $timeLine = $priceLine = $currentLine = "";
                $i = 0;
                foreach ($candles as $candle) {
//                if ($i % 50  == 0) {
//                    if ($period == "day1") {
//                        $timeLine .= "'" . (new \DateTime())->setTimestamp($candle['time'])->format('H:s') . "', ";
                        $timeLine .= "'" . (new \DateTime())->setTimestamp($candle['time'])->format($formats[$period]) . "', ";
//                    } else {
//                        $timeLine .= "'" . (new \DateTime())->setTimestamp($candle['time'])->format('M d') . "', ";
//                    }

                    $priceLine .= $candle['close'] . ", ";
                    $i++;
                }

                for ($i = 0; $i < count($candles); $i++) {
                    $currentLine .= $candle['close'] . ", ";
                }
//                $currentLine

                $formattedCandles[$key][$period]['currentline'] = substr($currentLine, 0, -2);
                $formattedCandles[$key][$period]['timeline'] = substr($timeLine, 0, -2);
                $formattedCandles[$key][$period]['priceline'] = substr($priceLine, 0, -2);
            }
        }

//        var_dump($formattedCandles);
//        die();

        return $formattedCandles;
    }

    /**
     * @param array $instruments
     *
     * @return array
     * @throws Exception\AnalyticsNotExistsException
     */
    public function getStats(array $instruments)
    {
        if (!$this->stats) {
            $bitfinex = new Bitfinex($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
            $bitfinex2 = new BitfinexV2($this->analytics->getAPIkey(), $this->analytics->getAPIsecret());
            $cryptCompare = new CryptCompare();

            $i = 0;
            $sortedInstruments = [];

            foreach ($instruments as $instrument) {
                if (!is_null($limit = self::ASSETS_LIMIT)) {
                    if ($i > $limit) continue;
                }
                $parts = explode("_", $instrument);

//                if ($instrument == "BTC_USD") {
                    $sortedInstruments[$i]['from'] = $parts[0];
                    $sortedInstruments[$i]['to'] = $parts[1];
                    $sortedInstruments[$i]['symbol'] = $parts[0] . $parts[1];
                    $sortedInstruments[$i]['instrument'] = $parts[0] . '/' . $parts[1];
//                }

                $i++;
            }

            $curDate = new \DateTime('now');
            $stats = [];

            foreach ($sortedInstruments as $sortedInstrument) {
//                $newTrades = $bitfinex2->get_trades($sortedInstrument["from"], $sortedInstrument["to"]);
                $ordersHistory = $bitfinex2->get_orders_history($sortedInstrument["from"], $sortedInstrument["to"]);

                $availableOrderIds = [];
                foreach ($ordersHistory as $order) {
                    $availableOrderIds[] = $order['id'];
                }

                $pastTrades = $bitfinex
                    ->getPastTrades(strtotime('-5000 day'), $curDate->getTimestamp(), 50, $sortedInstrument['symbol']);

//                foreach ( as $item) {
//
//                }

//                $totalBalanceUsd = $this->getCryptoBalanceUsd();

//                echo "<pre>";
//                var_dump("Orders history ".$sortedInstrument["from"].'/'.$sortedInstrument["to"]);
//                var_dump($ordersHistory);
//                var_dump("Past trades ".$sortedInstrument["from"].'/'.$sortedInstrument["to"]);
//                var_dump($pastTrades);
//                echo "</pre>";
//                die();

                /** @todo add check for action add account */
                if (isset($pastTrades["message"])) {
                    throw new Exception\AnalyticsNotExistsException('Bitfinex account does not exist');
                } elseif (isset($pastTrades["error"])) {
                    continue;
//                        throw new Exception\OverLimitException('Bitfinex limit exceeded. Try a minute later');
                }

                $j = 0;
                $skip = false;
                $totalPriceAmount["Buy"] = $totalPriceAmount["Sell"] = 0;
                $amount["Buy"] = $amount["Sell"] = 0;
                foreach ($pastTrades as $trade) {
                    if (!isset($trade["price"])) {
                        $skip = true;
                    }

                    if (!in_array($trade["order_id"], $availableOrderIds)) {
                        $skip = true;
                    }

//                    var_dump($trade);
//                    die();

//                    $order = $bitfinex2->get_order_trades($sortedInstrument["from"], $sortedInstrument["to"], $trade["order_id"]);
//                    var_dump($order);
//                    die();

                    $totalPriceAmount[$trade["type"]] += ($trade["price"] * $trade["amount"]);
                    $amount[$trade["type"]] += $trade["amount"];
                    $j++;
                }

                $totalPriceAmountResult = $totalPriceAmount["Buy"] - $totalPriceAmount["Sell"];
                $amountResult = $amount["Buy"] - $amount["Sell"];

//                var_dump($totalPriceAmountResult);
//                var_dump($amountResult);
//                die();

                /** @todo resolve while $amountResult = 0: cannot divide on null */
                if ($j != 0 && $amountResult >= 0.001 && !$skip) {
                    $middlePrice = $totalPriceAmountResult / $amountResult;
//                    var_dump($middlePrice);

                    $price = $cryptCompare->getSingleSymbolPrice($sortedInstrument['from'], $sortedInstrument['to']);

                    if (isset($price["Response"]["Message"])) {
                        throw new Exception\EmptyPriceException($price["Response"]["Message"].' while getting price '.$sortedInstrument['from'].'/'.$sortedInstrument['to']);
                    }

                    if (isset($price[$sortedInstrument['to']])) {
                        $currentPrice = floatval($price[$sortedInstrument['to']]);

                        /**
                         * @var int|float $currentPrice
                         */
                        $parts = explode('.', $currentPrice);
                        if (isset($parts[1])) {
                            $pow = strlen((string)$parts[1]);
                        } else {
                            $pow = 0;
                        }

                        $delta = $middlePrice - $currentPrice;
//                        $deltaSeparate = $delta*$cryptCompare->getSingleSymbolPrice($sortedInstrument['from'], $sortedInstrument['to'])[$sortedInstrument['to']];
                        $deltaPercent = $delta / $middlePrice * 100;

                        $deltaOpposite = $amountResult / 100 * $deltaPercent;

//                        $delta = $middlePrice - $currentPrice;
//                        $deltaPercent = $delta / $middlePrice * 100;

                        $stats[$sortedInstrument['instrument']]['middle_price'] = $middlePrice;
                        $stats[$sortedInstrument['instrument']]['current_price'] = $currentPrice;

                        $stats[$sortedInstrument['instrument']]['amount'] = $amountResult;

                        $stats[$sortedInstrument['instrument']]['delta'] = $deltaOpposite;
                        $stats[$sortedInstrument['instrument']]['delta_percent'] = $deltaPercent;

                        $stats[$sortedInstrument['instrument']]['arrow'] = ($delta < 0) ? 'down' : 'up';
                        $stats[$sortedInstrument['instrument']]['pow'] = $pow;
                    }
                }
            }

            $this->stats = $stats;
        }

        return $this->stats;
    }
}