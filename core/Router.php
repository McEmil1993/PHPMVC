<?php
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/AuthController.php';
class Router {
    protected $routes = [];

    public function __construct() {
        // Ito ang puwang para sa pag-set ng mga ruta ng iyong application
        $this->setRoutes();
    }

    protected function setRoutes() {
        // Ito ang lugar kung saan ididagdag mo ang iyong mga ruta
        // Halimbawa:
        $this->routes = [
            '/' => 'HomeController@index', // Home route
            '/home' => 'HomeController@index', // Home route
            '/login' => 'AuthController@login', // Login route
            '/logout' => 'AuthController@logout', // Login route
            '/register' => 'AuthController@register' // Register route
        ];
    }

    public function run() {
        // Kunin ang URI ng current request
        $uri = $_SERVER['REQUEST_URI'];

        // Tanggalin ang query string sa URI kung meron
        $uri = strtok($uri, '?');

        // Hanapin ang route sa mga naka-set na routes
        foreach ($this->routes as $route => $action) {
            // Kung ang URI ay tumutugma sa isang route, i-dispatch ang request
            if ($uri === $route) {
                $this->dispatch($action);
                return;
            }
        }

        // Kung walang tumutugma sa mga naka-set na routes, ipasa ang request sa 404 page
        $this->error404();
    }

    protected function dispatch($action) {
        // Paghiwalayin ang controller at action
        $parts = explode('@', $action);
        $controllerName = $parts[0]; // Kunin ang pangalan ng controller
        $method = $parts[1]; // Kunin ang pangalan ng method
    
        // Lumikha ng instance ng controller (hindi na kailangan ang pangalan ng namespace)
        $controller = new $controllerName();
    
        // Tawagin ang action
        $controller->$method();
    }

    protected function error404() {
        // Mag-redirect sa 404 page o ipakita ang isang custom error message
        header("HTTP/1.0 404 Not Found");
        echo '404 Page Not Found';
        exit();
    }
}
?>