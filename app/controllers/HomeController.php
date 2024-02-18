<?php

require_once '../core/Session.php';
require_once '../core/Controller.php';

class HomeController extends Controller  {

    public function index() {
        // Start session
        Session::start();
        $user_role = Session::getUserRole();
        $data = [
            'title' => 'Home Page',
            'content' => 'Welcome to the home page!',
            'user_role' => $user_role
        ];
        // Check if user is logged in
        if (Session::isLoggedIn()) {
            // Get user role
            // $user_role = Session::getUserRole();

            // Display home page based on user role
            switch ($user_role) {
                case 'admin':
                    // Code para sa home page ng admin
                    include '../views/home/admin_home.php';
                    break;
                case 'cashier':
                    // Code para sa home page ng cashier
                    include '../views/home/cashier_home.php';
                    break;
                case 'customer':
                    // Code para sa home page ng customer
                    include '../views/home/customer_home.php';
                    break;
                default:
                    // Code para sa default home page
                    $this->renderView('home', $data);
            }
        } else {
            // Redirect to login page if user is not logged in
            $this->renderView('auth/login', $data);
            exit();
        }
    }
}
?>