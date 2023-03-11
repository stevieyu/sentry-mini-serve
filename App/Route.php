<?php declare(strict_types=1);

namespace App;

class Route
{
    public function __construct()
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $this->routers($r);
        });

        $this->dispatch($dispatcher);
    }

    public function routers(\FastRoute\RouteCollector $r)
    {
        include __DIR__ . '/routers.php';
    }

    protected function dispatch(\FastRoute\Dispatcher $dispatcher)
    {
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = preg_replace('/\?.+/', '', $uri);
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo '404';
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo '405';
                break;
            case \FastRoute\Dispatcher::FOUND:
                if ($routeInfo[1] instanceof \Closure) {
                    $args = $routeInfo[2] ?? [];
                    $input = file_get_contents('php://input');
                    $input = zlib_decode($input) ?: $input;
                    $post = array_merge($_POST, json_decode($input, true) ?: []);
                    $res = $routeInfo[1]($args, $post);
                    if (is_array($res)) $res = json_encode($res, JSON_UNESCAPED_UNICODE);
                    echo $res;
                } else {
                    dd($routeInfo);
                }

                break;
        }
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