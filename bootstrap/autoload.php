<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $map = [
        'App\\' => 'app',
        'Bootstrap\\' => 'bootstrap',
    ];

    $baseDir = null;
    $relative = null;
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $baseDir = $dir;
            $relative = substr($class, strlen($prefix));
            break;
        }
    }

    if ($baseDir === null || $relative === null) {
        return;
    }

    $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
    $file = BASE_PATH . DIRECTORY_SEPARATOR . $baseDir . DIRECTORY_SEPARATOR . $relativePath;

    if (is_file($file)) {
        require $file;
    }
});
