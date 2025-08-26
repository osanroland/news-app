<?php

namespace App\Routing;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $p, callable $h): void { $this->map('GET', $p, $h); }
    public function post(string $p, callable $h): void { $this->map('POST', $p, $h); }

    private function map(string $m, string $pattern, callable $h): void {
        $regex = "#^" . preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern) . "$#";
        $this->routes[$m][] = [$regex, $h];
    }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes[strtoupper($method)] ?? [] as [$regex, $handler]) {
            if (preg_match($regex, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array($handler, $params);
                return;
            }
        }
        http_response_code(404); echo "404 Not Found";
    }
}
