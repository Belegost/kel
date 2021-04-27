<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 08.05.2018
 * Time: 22:46
 */

namespace App\Request;

use App\Lib\XMLHttpRequest;

class NewOrder extends XMLHttpRequest {
    private $product_quantity;
    private $product_code;

    protected function validate() {
        if (empty($this->product_quantity) || empty($this->product_code)) {
            $this->addError('Incomplete request data. Please fill all required fields and try again');
        }
    }

    /**
     * @return mixed
     */
    public function getProductQuantity() {
        return $this->product_quantity;
    }

    /**
     * @param mixed $product_quantity
     */
    public function setProductQuantity($product_quantity) {
        $this->product_quantity = intval($product_quantity);
    }

    /**
     * @return mixed
     */
    public function getProductCode() {
        return $this->product_code;
    }

    /**
     * @param mixed $product_code
     */
    public function setProductCode($product_code) {
        $this->product_code = $product_code;
    }
}