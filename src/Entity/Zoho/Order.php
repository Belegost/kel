<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 25.04.2018
 * Time: 03:02
 */

namespace App\Entity\Zoho;


class Order extends ZohoEntity {
    protected function build() {
        $this['Product Details'] = [['@type'=>'product']];
        $this['VENDORID'] = '102555000000134449';
    }

    /**
     * @return string
     */
    public function getId(): ?string {
        return $this['PURCHASEORDERID'] ?? null;
    }

    /**
     * @param string $value
     * @return Order
     */
    public function setId(string $value): Order {
        $this['PURCHASEORDERID'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): ?string {
        return $this['Subject'] ?? null;
    }

    /**
     * @param string $value
     * @return Order
     */
    public function setSubject(string $value): Order {
        $this['Subject'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactID(): ?string {
        return $this['CONTACTID'] ?? null;
    }

    /**
     * @param string $value
     * @return Order
     */
    public function setContactID(string $value): Order {
        $this['CONTACTID'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string {
        return $this['Status'] ?? null;
    }

    /**
     * @param string $value
     * @return Order
     */
    public function setStatus(string $value): Order {
        $this['Status'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductId(): ?string {
        return $this['Product Details'][0]['Product Id'] ?? null;
    }

    /**
     * @param string $value
     * @return Order
     */
    public function setProductId(string $value): Order {
        $this['Product Details'][0]['Product Id'] = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductQuantity(): ?int {
        return $this['Product Details'][0]['Quantity'] ?? null;
    }

    /**
     * @param $value
     * @return Order
     */
    public function setProductQuantity(int $value): Order {
        $this['Product Details'][0]['Quantity'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductDescription(): ?string {
        return $this['Product Details'][0]['Product Description'] ?? null;
    }

    /**
     * @param $value
     * @return Order
     */
    public function setProductDescription(string $value): Order {
        $this['Product Details'][0]['Product Description'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductName(): ?string {
        return $this['Product Details'][0]['Product Name'] ?? null;
    }

    /**
     * @param $value
     * @return Order
     */
    public function setProductName(string $value): Order {
        $this['Product Details'][0]['Product Name'] = $value;
        return $this;
    }
}