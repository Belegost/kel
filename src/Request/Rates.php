<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 22:46
 */

namespace App\Request;

use App\Lib\XMLHttpRequest;

class Rates extends XMLHttpRequest {
    private $currencies;

    protected function validate() {
        if (empty($this->currencies) || !is_array($this->currencies)) {
            $this->addError('Please type currencies list');
        }
    }

    /**
     * @return array
     */
    public function getCurrencies(): ?array {
        return $this->currencies;
    }

    /**
     * @param array $currencies
     * @return Rates
     */
    public function setCurrencies(array $currencies): ?Rates {
        $this->currencies = $currencies;
        return $this;
    }
}