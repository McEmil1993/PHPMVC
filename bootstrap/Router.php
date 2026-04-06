<?php
declare(strict_types=1);

namespace Bootstrap;

final class Router
{
    /** @var array<int, array{method:string, pattern:string, regex:string, params:array<int,string>, handler:callable}> */
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function put(string $pattern, callable $handler): void
    {
        $this->add('PUT', $pattern, $handler);
    }

    public function delete(string $pattern, callable $handler): void
    {
        $this->add('DELETE', $pattern, $handler);
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['params'] as $name) {
                $params[] = $matches[$name] ?? null;
            }

            call_user_func_array($route['handler'], $params);
            return;
        }

        $accept = (string)($_SERVER['HTTP_ACCEPT'] ?? '');
        $wantsJson = str_contains($accept, 'application/json') || str_contains($accept, 'text/json');
        $isApi = str_starts_with($path, '/api');

        if ($isApi || $wantsJson) {
            Response::json([
                'success' => false,
                'message' => 'Not Found',
                'data' => (object)[],
            ], 404);
            return;
        }

        try {
            View::render('errors/404', [
                'path' => $path,
            ], 404);
        } catch (\Throwable) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=utf-8');
            echo '404 Not Found';
        }
    }

    private function add(string $method, string $pattern, callable $handler): void
    {
        [$regex, $params] = $this->compilePattern($pattern);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'regex' => $regex,
            'params' => $params,
            'handler' => $handler,
        ];
    }

    /** @return array{0:string, 1:array<int,string>} */
    private function compilePattern(string $pattern): array
    {
        $paramNames = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $m) use (&$paramNames): string {
            $paramNames[] = $m[1];
            return '(?P<' . $m[1] . '>[^/]+)';
        }, $pattern);

        $regex = '#^' . $regex . '$#';
        return [$regex, $paramNames];
    }
}
