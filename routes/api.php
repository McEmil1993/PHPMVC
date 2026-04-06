<?php
declare(strict_types=1);

use App\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Bootstrap\Router;

/** @var Router $router */
/** @var callable():PDO $pdoFactory */

$makeUserController = static function () use ($pdoFactory): UserController {
    static $controller = null;
    if ($controller instanceof UserController) {
        return $controller;
    }

    $pdo = $pdoFactory();
    $repo = new UserRepository($pdo);
    $service = new UserService($repo);
    $controller = new UserController($service);

    return $controller;
};

$router->get('/api/users', static function () use ($makeUserController): void {
    $makeUserController()->index();
});

$router->post('/api/users', static function () use ($makeUserController): void {
    $makeUserController()->store();
});

$router->put('/api/users/{id}', static function (string $id) use ($makeUserController): void {
    $makeUserController()->update($id);
});

$router->delete('/api/users/{id}', static function (string $id) use ($makeUserController): void {
    $makeUserController()->destroy($id);
});
