<?php declare(strict_types=1);

namespace App;

class Bootstrap
{
    public function __construct(){
        Route::start();
    }

    private static $instance = null;
    public static function start(): object
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}