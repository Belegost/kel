<?php

namespace App\Lib\MenuManager;

/**
 * Class Attributes
 */
class Attributes extends \ArrayObject
{
    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString()
    {
        $result = [];

        foreach ($this as $attr => $value) {
            $result[] = "{$attr}='{$value}'";
        }

        return implode(' ', $result);
    }
}