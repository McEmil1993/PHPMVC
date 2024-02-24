<?php
require_once '../core/Controller.php';
require_once '../app/models/User.php';
require_once '../core/Session.php';

class AuthController extends Controller {

    public function __construct() {
        Session::start();
        if (Session::isLoggedIn()) {
            header('Location: /');
        }
    }

    public function login() { 

        $data = [
            'title' => 'Login Page',
            'content' => 'Welcome to the Login page!'
        ];
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $username = $_POST['username'];
            $password = $_POST['password'];
            $message = "";
            // Check if user exists
            $user = new User();
            $loginUSer = $user->loginUser($username,$password);
            if ($loginUSer == 1) {
                $message = 'Success login!';
            }elseif ($loginUSer == 2) {
                $message = 'Wrong password!';
            }else{
                $message = 'User not exist!';
            }

            echo json_encode([
                'status' => $loginUSer,
                'message' => $message
            ]);
            
        } else {
            // Display login form
            $this->renderView('auth/login', $data);
            // include '../views/auth/login.php';
        }
    }

    public function register() {
        // Check if form is submitted
        $data = [
            'status' => 'Login Page',
            'content' => 'Welcome to the Login page!'
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $data = [
                'firstname' => $_POST['firstname'],
                'middlename' => $_POST['middlename'],
                'lastname' => $_POST['lastname'],
                'contact' => $_POST['contact'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'role' => 'admin' // Default role for registered users
            ];
    
            // Create a new instance of User model
            $user = new User();
    
            // Call the registerUser method of the User model
            $result = $user->registerUser($data);
    
            // Check the result of registration
            if ($result) {
                // $this->renderView('auth/register', []);
                echo json_encode([
                    'status' => 1,
                    'message' => 'Success!'
                ]);
                // You may want to redirect the user to the login page or some other page after successful registration
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Error!'
                ]);
            }
        } else {
            // Display registration form
            $this->renderView('auth/register', []); // Adjust the path and data as needed
        }
    }

    // public function register() {
    //     // Check if form is submitted
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Get form data
    //         $username = $_POST['username'];
    //         $email = $_POST['email'];
    //         $password = $_POST['password'];
    //         $confirm_password = $_POST['confirm_password'];
    //         $role = 'customer'; // Default role for new users, you may change this based on your requirements
    
    //         // Validate form data
    //         if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    //             echo "All fields are required";
    //             return;
    //         }
    
    //         if ($password !== $confirm_password) {
    //             echo "Passwords do not match";
    //             return;
    //         }
    
    //         // Check if username or email is already taken
    //         $user = new User();
    //         $existingUser = $user->getUserByUsername($username);
    //         $existingEmail = $user->getUserByEmail($email);
    
    //         if ($existingUser) {
    //             echo "Username already taken";
    //             return;
    //         }
    
    //         if ($existingEmail) {
    //             echo "Email already registered";
    //             return;
    //         }
    
    //         // Hash password
    //         $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    //         // Add user to database
    //         $result = $user->addUser($username, $email, $hashed_password, $role);
    
    //         if ($result) {
    //             echo "Registration successful";
    //             // Redirect to login page or any other page you desire
    //             header("Location: /login");
    //             exit();
    //         } else {
    //             echo "Registration failed";
    //         }
    //     } else {
    //         $this->renderView('auth/register', []);
    //     }
    // }

    public function logout() { 
        Session::destroy();
    }
}
?>