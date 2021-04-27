<?php

namespace App\Lib;

/**
 * Class RESTService
 * @package App\Lib
 *
 * @todo merge with PPF
 */
class RESTService
{
    /**
     * Api host
     */
    const API_HOST = 'https://bit-flow.io';

    /**
     * @var array
     */
    public static $timeFrames = [
        1 => 1,
        5 => 5,
        15 => 15,
        30 => 30,
        60 => 60,
        1440 => 1440,
    ];

    public function getExchangesList()
    {
        $exchangesList = @file_get_contents(self::API_HOST.'/api/fetch/exchanges');

        if ($exchangesList === false) {

            $exchangesList = '{}';
        }

//        var_dump($exchangesList);
//        die();

        return json_decode($exchangesList, true);
    }

    public function getInstrumentsList(int $exchangeId)
    {
        $apiLink = self::API_HOST.'/api/fetch/exchanges/pairs/'.$exchangeId;


        $pairsList = @file_get_contents($apiLink);

        if ($pairsList === false) {

            $pairsList = '{}';
        }

//        var_dump($pairsList);
//        die();


        return json_decode($pairsList, true);
    }

    public function getCandles(int $exchangeId, string $pair, string $timeFrame, int $limit = 1, int $offset = 0)
    {
        $apiLink = self::API_HOST.'/api/fetch/candles/limit?exchange_id='.$exchangeId.'&pair='.$pair.'&frame='.$timeFrame.'&limit='.$limit.'&offset='.$offset;
        $candles = @file_get_contents($apiLink);

        if ($candles === false) {

            $candles = '{}';
        }

//        var_dump($candles);
//        die();

        return json_decode($candles, true);
    }
}