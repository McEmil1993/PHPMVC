<?php
declare(strict_types=1);

require __DIR__ . '/autoload.php';

use Bootstrap\Response;
use Bootstrap\Router;
use Bootstrap\Session;

set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(static function (Throwable $e): void {
    Response::json([
        'success' => false,
        'message' => 'Server Error',
        'data' => (object)[],
    ], 500);
});

$config = require BASE_PATH . '/config/config.php';

Session::start();

/** @var callable():PDO $pdoFactory */
$pdoFactory = static function () use ($config): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db = $config['db'] ?? [];
    $host = (string)($db['host'] ?? '127.0.0.1');
    $port = (string)($db['port'] ?? '3306');
    $database = (string)($db['database'] ?? 'app');
    $username = (string)($db['username'] ?? 'root');
    $password = (string)($db['password'] ?? '');
    $charset = (string)($db['charset'] ?? 'utf8mb4');

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
};

$router = new Router();
(static function (Router $router, callable $pdoFactory): void {
    $webRoutes = BASE_PATH . '/routes/web.php';
    if (is_file($webRoutes)) {
        require $webRoutes;
    }

    require BASE_PATH . '/routes/api.php';
})($router, $pdoFactory);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

$router->dispatch($method, $path);
