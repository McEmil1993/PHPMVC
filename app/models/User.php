<?php

require_once '../core/Database.php';
require_once '../core/Session.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserById($id) {
        $this->db->query("SELECT * FROM tb_accounts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function registerUser($data) {
        // Extract data from $data array
        $firstname = $data['firstname'];
        $middlename = $data['middlename'];
        $lastname = $data['lastname'];
        $contact = $data['contact'];
        $username = $data['username'];
        $password = $data['password'];
        $role = $data['role'];
    
        // Check if the username already exists
        $this->db->query("SELECT id FROM tb_accounts WHERE username = :username");
        $this->db->bind(':username', $username);
        $this->db->execute();
        $row = $this->db->single();
        if ($row) {
            // Username already exists
            return false;
        }
    
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Get current timestamp
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = $createdAt; // Initially set to created_at timestamp
    
        // Insert into tb_accounts
        $this->db->query("INSERT INTO tb_accounts (username, password, role, created_at) VALUES (:username, :password, :role, :created_at)");
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':role', $role);
        $this->db->bind(':created_at', $createdAt);
        $this->db->execute();
    
        // Get the id of the inserted user
        $accountId = '';

        $this->db->query("SELECT id FROM tb_accounts WHERE username = :username");
        $this->db->bind(':username', $username);
        $this->db->execute();
        $row = $this->db->single();

        if ($row) {
            $accountId = $row['id']; // Get the id here
            // Now you can use $id for further processing
        }
    
        // Insert into tb_admins
        $this->db->query("INSERT INTO tb_admins (account_id, firstname, middlename, lastname, contact, created_at) VALUES (:account_id, :firstname, :middlename, :lastname, :contact, :created_at)");
        $this->db->bind(':account_id', $accountId);
        $this->db->bind(':firstname', $firstname);
        $this->db->bind(':middlename', $middlename);
        $this->db->bind(':lastname', $lastname);
        $this->db->bind(':contact', $contact);
        $this->db->bind(':created_at', $createdAt);
        
        // Execute
        return $this->db->execute();
    }

    public function loginUser($username, $password) {

        $this->db->query("SELECT * FROM tb_accounts WHERE username = :username");
        $this->db->bind(':username', $username);
        $user = $this->db->single();
        // Session::start();
        // Session::setUser($existingUser);
        if ($user) {
            // Verify password
            $hashedPassword = $user['password'];
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, return user information
                Session::start();

                $account_id =  $user['id'];
                $this->db->query("SELECT * FROM tb_admins WHERE account_id = :account_id");
                $this->db->bind(':account_id', $account_id);
                $userCred = $this->db->single();

                $data = [
                    'account_id' => $userCred['account_id'],
                    'firstname' => $userCred['firstname'],
                    'middlename' => $userCred['middlename'],
                    'lastname' => $userCred['lastname'],
                    'contact' => $userCred['contact'],
                    'username' => $user['username'],
                    'password' => $user['password'],
                    'role' => $user['role']
                ];

                Session::setUser($data);

                return 1;
            }else{
                return 2; // Wrong password
            }
        }
    
        return 3; // username not exist
    }

}
?>