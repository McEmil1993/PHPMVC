<?php
foreach (glob('../app/controllers/*.php') as $filename) {
    require_once $filename;
}
class Router {
    protected $routes = [];

    public function __construct() {
        $this->setRoutes();
    }

    protected function setRoutes() {
      
        $this->routes = [
            '/' => 'HomeController@index', 
            '/home' => 'HomeController@index', 
            '/login' => 'AuthController@login', 
            '/logout' => 'AuthController@logout',
            '/register' => 'AuthController@register', 
            '/users' => 'UserController@index',
            '/add-user' => 'UserController@addUser',
            '/edit-user' => 'UserController@getUserId',
            '/update-user' => 'UserController@updateUser',
            '/shops' => 'ShopOwnerController@index',
            '/template' => 'TemplateController@index',
        ];
    }

    public function run() {

        $uri = $_SERVER['REQUEST_URI'];

        $uri = strtok($uri, '?');

        foreach ($this->routes as $route => $action) {
            
            if ($uri === $route) {
                $this->dispatch($action);
                return;
            }
        }

        $this->error404();
    }

    protected function dispatch($action) {
        $parts = explode('@', $action);
        $controllerName = $parts[0]; 
        $method = $parts[1]; 
        $controller = new $controllerName();
        $controller->$method();
    }

    protected function error404() {
        header("HTTP/1.0 404 Not Found");
        echo '404 Page Not Found';
        exit();
    }
}
?>