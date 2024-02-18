<?php
class Session {
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
         // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Unset cookies related to session (if any)
        setcookie(session_name(), '', time() - 3600, '/');
    }

    public static function isLoggedIn() {
        return isset($_SESSION['account_id']);
    }

    public static function setUser($user) {
        self::set('account_id', $user['account_id']);
        self::set('firstname', $user['firstname']);
        self::set('middlename', $user['middlename']);
        self::set('lastname', $user['lastname']);
        self::set('contact', $user['contact']);
        self::set('username', $user['username']);
        self::set('password', $user['password']);
        self::set('role', $user['role']);
       
    }

    public static function getUserRole() {
        return self::get('user_role');
    }
}
?>