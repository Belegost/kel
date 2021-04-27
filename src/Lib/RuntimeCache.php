<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 12.06.2018
 * Time: 22:03
 */

namespace App\Lib;


class RuntimeCache {
    private static $storage = [];

    public static function get(string $key, $default = null) {
        return self::has($key) ? self::$storage[$key] : $default;
    }

    public static function set(string $key, $value) {
        self::$storage[$key] = $value;
    }

    public static function has(string $key): bool {
        return isset(self::$storage[$key]);
    }

    public static function makeKey(array $parts) {
        return md5(implode('-', array_merge([get_called_class()], $parts)));
    }
}