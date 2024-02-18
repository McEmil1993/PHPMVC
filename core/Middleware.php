<?php
class Middleware {
    public static function authenticate($role = 'customer') {
        // Simula ng session
        session_start();

        // Check kung may user na naka-login
        if (!isset($_SESSION['user_id'])) {
            // Redirect sa login page kung walang user na naka-login
            header("Location: /login");
            exit();
        }

        // Kunin ang role ng user mula sa session
        $user_role = $_SESSION['user_role'];

        // Check kung ang role ng user ay tumutugma sa hinahanap na role
        if ($user_role !== $role) {
            // Redirect sa appropriate dashboard depende sa role
            switch ($user_role) {
                case 'admin':
                    header("Location: /admin/dashboard");
                    break;
                case 'cashier':
                    header("Location: /cashier/dashboard");
                    break;
                case 'customer':
                    header("Location: /customer/dashboard");
                    break;
                default:
                    // Redirect sa default dashboard para sa mga hindi kilalang role
                    header("Location: /dashboard");
            }
            exit();
        }
    }
}
?>