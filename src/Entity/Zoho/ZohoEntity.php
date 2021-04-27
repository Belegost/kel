<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 25.04.2018
 * Time: 03:03
 */

namespace App\Entity\Zoho;


abstract class ZohoEntity extends \ArrayObject {
    abstract protected function build();

    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator") {
        parent::__construct($input, $flags, $iterator_class);
        $this->build();
    }

    public function createRecord(array $bind = [], bool $skipEmpty = true): array {
        $record = [];
        foreach ($this->generateRecord($bind, $skipEmpty) as $field => $value) {
            $record[$field] = $value;
        }

        return $record;
    }

    protected function generateRecord(array $bind = [], bool $skipEmpty = true) {
        foreach ($bind as $field => $value) {
            $this[$field] = $value;
        }

        foreach ($this as $field => $value) {
            if (is_null($value) && $skipEmpty)
                continue;

            yield $field => $value;
        }
    }
}