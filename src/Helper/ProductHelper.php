<?php

namespace App\Helper;

/**
 * Class ProductHelper
 */
class ProductHelper
{
    /**
     * Returns products pricing table
     *
     * @param array $products
     *
     * @return array
     */
    public static function getPricingTable(array $products)
    {
        $result = [];

        foreach (static::generatePricingTableData($products) as $product => $data) {
            if (!isset($result[$product])) {
                $result[$product] = [
                    'period' => [],
                    'price' => [],
                    'setup_fee' => [],
                    'closing_fee' => [],
                    'penalty' => [],
                    'share_profit' => [],
                ];
            }

            $result[$product]['period'][$data['period']['name']] = $data['period']['value'];
            $result[$product]['price'][$data['period']['name']] = $data['price']['value'];
            $result[$product]['setup_fee'][$data['period']['name']] = $data['setup_fee']['value'];
            $result[$product]['closing_fee'][$data['period']['name']] = $data['closing_fee']['value'];
            $result[$product]['penalty'][$data['period']['name']] = $data['penalty']['value'];
            $result[$product]['share_profit'][$data['period']['name']] = $data['share_profit']['value'];
        }

        return $result;
    }

    /**
     * Returns products pricing
     *
     * @param array $products
     *
     * @return array
     */
    public static function getProductsPricing(array $products)
    {
        $result = [];

        foreach (static::generateProductsPricingData($products) as $period => $data) {
            if (!isset($result[$period])) {
                $result[$period] = [];
            }

            $result[$period][$data['type']] = $data['pricing'];
        }

        return $result;
    }

    /**
     * Returns product pricing
     *
     * @param string $type
     * @param array $products
     *
     * @return array
     */
    public static function getProductPricing(string $type, array $products)
    {
        $result = [
            'period' => [],
            'price' => [],
            'setup_fee' => [],
            'closing_fee' => [],
            'penalty' => [],
            'share_profit' => [],
        ];

        foreach (static::generateProductPricingData($type, $products) as $period => $data) {
            $result['period'][$data['period']['name']] = $data['period']['value'];
            $result['price'][$data['period']['name']] = $data['price']['value'];
            $result['setup_fee'][$data['period']['name']] = $data['setup_fee']['value'];
            $result['closing_fee'][$data['period']['name']] = $data['closing_fee']['value'];
            $result['penalty'][$data['period']['name']] = $data['penalty']['value'];
            $result['share_profit'][$data['period']['name']] = $data['share_profit']['value'];
        }

        return $result;
    }

    /**
     * Generates pricing data
     *
     * @param array $products
     *
     * @return \Generator
     */
    protected static function generatePricingTableData(array $products)
    {
        foreach ($products as $product) {
            $periodNum = (int)floor($product['period']['days'] / 30);
            $periodName = 'month_' . $periodNum;

            yield $product['type']['name'] => [
                'period' => ['name' => $periodName, 'value' => $periodNum],
                'price' => ['name' => $periodName, 'value' => $product['price']],
                'setup_fee' => ['name' => $periodName, 'value' => $product['setup_fee']],
                'closing_fee' => ['name' => $periodName, 'value' => $product['closing_fee']],
                'penalty' => ['name' => $periodName, 'value' => $product['penalty']],
                'share_profit' => ['name' => $periodName, 'value' => $product['share_profit']],
            ];
        }
    }

    /**
     * Generates products pricing data
     *
     * @param array $products
     *
     * @return \Generator
     */
    protected static function generateProductsPricingData(array $products)
    {
        foreach ($products as $product) {
            $periodNum = (int)floor($product['period']['days'] / 30);
            $periodName = $periodNum . 'm';

            yield $periodName => [
                'type' => $product['type']['type'],
                'pricing' => [
                    'price' => $product['price'],
                    'fee' => $product['setup_fee'] . '%',
                    'closing' => $product['closing_fee'] . '%',
                    'penalty' => $product['penalty'] . '%',
                    'share' => $product['share_profit'] . '%',
                ],
            ];
        }
    }

    /**
     * Generates products pricing data
     *
     * @param string $type
     * @param array $products
     *
     * @return \Generator
     */
    protected static function generateProductPricingData(string $type, array $products)
    {
        foreach ($products as $product) {
            if ($product['type']['type'] !== $type) {
                continue;
            }

            $periodNum = (int)floor($product['period']['days'] / 30);
            $periodName = 'month_' . $periodNum;

            yield [
                'period' => ['name' => $periodName, 'value' => $periodNum],
                'price' => ['name' => $periodName, 'value' => $product['price']],
                'setup_fee' => ['name' => $periodName, 'value' => $product['setup_fee']],
                'closing_fee' => ['name' => $periodName, 'value' => $product['closing_fee']],
                'penalty' => ['name' => $periodName, 'value' => $product['penalty']],
                'share_profit' => ['name' => $periodName, 'value' => $product['share_profit']],
            ];
        }
    }


}
