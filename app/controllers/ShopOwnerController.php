<?php

require_once '../core/Session.php';
require_once '../core/Controller.php';

class ShopOwnerController extends Controller  {
    private $user_role;
    public function __construct() {
        // Start session
        Session::start();
        if (!Session::isLoggedIn()) {
            $this->renderView('auth/login',[]);
            exit();
        }
        $this->user_role = Session::getUserRole();
    }

    public function index() {
        $data = [
            'title' => 'Shops Management',
            'status' => 'active',
            'content' => 'Welcome to the Template!',
            'user_role' => $this->user_role
        ];

        $this->renderView('pages/shop_owners/index', $data);

       
    }
}
?>