<?php declare(strict_types=1);
namespace App;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ERROR);

class Bootstrap
{
    public function __construct(){
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Origin: *');
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