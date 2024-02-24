<?php

require_once '../core/Session.php';
require_once '../core/Controller.php';
require_once '../app/models/User.php';

class UserController extends Controller  {
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
        $user = new User();
        $users = $user->getAllUsers();
        $data = [
            'title' => 'User Management',
            'status' => 'active',
            'content' => 'Welcome to the Template!',
            'user_role' => $this->user_role,
            'datas' => $users
        ];
        // echo json_encode($data);

        $this->renderView('pages/user_management/index', $data);

    }

    public function addUser() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $data = [
                'firstname' => $_POST['firstname'],
                'middlename' => $_POST['middlename'],
                'lastname' => $_POST['lastname'],
                'contact' => $_POST['contact'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'role' =>  $_POST['role'], // Default role for registered users
            ];
    
            // Create a new instance of User model
            $user = new User();
    
            // Call the registerUser method of the User model
            $result = $user->registerUser($data);

            if ($result) {
                // $this->renderView('auth/register', []);
                echo json_encode([
                    'status' => 1,
                    'message' => 'Success!'
                ]);

            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Username already exist!'
                ]);
            }
        } else {
            // Display registration form
            $this->renderView('auth/register', []); // Adjust the path and data as needed
        }
    }
    public function getUserId() {
        $get = [
            'admin_id' =>  $_POST['admin_id'],
            'account_id' =>  $_POST['account_id'],
        ];
        $user = new User();
        $data = $user->getUserById($get);
        
        echo json_encode($data);

    }
}
?>