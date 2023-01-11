<?php

namespace App;

/**
 * https://sleekdb.github.io/#/getting-started
 * @mixin \SleekDB\Store
 */
class Store
{
    /**
     * @var null|\SleekDB\Store
     */
    private static $instance = null;
    public static function __callStatic($name, $arguments)
    {
        if (empty(self::$instance)) {
            self::$instance = new \SleekDB\Store('app', '/tmp/SleekDB');
        }
        return self::$instance->$name(...$arguments);
    }
}