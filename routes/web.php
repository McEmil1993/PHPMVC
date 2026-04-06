<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use Bootstrap\Router;
use Bootstrap\Session;
use Bootstrap\View;

/** @var Router $router */
/** @var callable():PDO $pdoFactory */

$makeAuthController = static function () use ($pdoFactory): AuthController {
    static $controller = null;
    if ($controller instanceof AuthController) {
        return $controller;
    }

    $controller = new AuthController($pdoFactory);
    return $controller;
};

/** @param callable():void $next */
$requireAuth = static function (callable $next): callable {
    return static function () use ($next): void {
        $user = Session::get('auth_user');
        if (!is_array($user)) {
            header('Location: /login', true, 302);
            return;
        }

        $next();
    };
};

$router->get('/', static function (): void {
    $user = Session::get('auth_user');
    if (!is_array($user)) {
        header('Location: /login', true, 302);
        return;
    }

    header('Location: /home', true, 302);
});

$router->get('/home', $requireAuth(static function (): void {
    View::render('home', []);
}));

$router->get('/login', static function () use ($makeAuthController): void {
    $makeAuthController()->showLogin();
});

$router->post('/login', static function () use ($makeAuthController): void {
    $makeAuthController()->login();
});

$router->post('/logout', static function () use ($makeAuthController): void {
    $makeAuthController()->logout();
});

$router->get('/logout', static function () use ($makeAuthController): void {
    $makeAuthController()->logout();
});

$router->get('/users', $requireAuth(static function (): void {
    View::render('users/index', []);
}));
