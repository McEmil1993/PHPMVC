<?php

require_once '../core/Database.php';
require_once '../core/Session.php';
require_once '../db/QueryBuilder.php';
class User {

    protected $fillable = [''];
    private $queryBuilder;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->queryBuilder = QueryBuilder('users');
    }

    public function getAllUsers() {
       $id =  Session::getUserID();
        $sql = "SELECT * ,adm.id as admin_id
                FROM tb_accounts AS acc
                JOIN tb_admins AS adm ON acc.id = adm.account_id 
                WHERE adm.account_id != '".$id."'";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getUserById($data) {
        $this->db->query("SELECT * ,adm.id as admin_id
        FROM tb_accounts AS acc
        JOIN tb_admins AS adm ON acc.id = adm.account_id WHERE adm.id = :admin_id and account_id = :account_id");
        $this->db->bind(':admin_id', $data['admin_id']);
        $this->db->bind(':account_id', $data['account_id']);
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

                if($user['is_deleted'] == 1){
                    return 3; 
                }else{
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
                }

            }else{
                return 2; // Wrong password
            }
        }
    
        return 3; // username not exist
    }


    public function updateUser($data) {
        // Extract data from $data array
        $admin_id = $data['admin_id'];
        $account_id = $data['account_id'];
        $firstname = $data['firstname'];
        $middlename = $data['middlename'];
        $lastname = $data['lastname'];
        $contact = $data['contact'];
        $username = $data['username'];
        $password = $data['password'];
        $role = $data['role'];
        $is_deleted = $data['is_deleted'];
        
    
        // Check if the username already exists
        $this->db->query("SELECT id FROM tb_accounts WHERE username = :username AND id != :account_id" );
        $this->db->bind(':username', $username);
        $this->db->bind(':account_id', $account_id);
        $this->db->execute();
        $row = $this->db->single();
        if ($row) {
            // Username already exists
            return false;
        }
    
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Get current timestamp
        $updatedAt = date('Y-m-d H:i:s');
        
        if($password == '') {
            $this->db->query("UPDATE tb_accounts SET username = :username, updated_at = :updated_at ,role = :role ,is_deleted = :is_deleted  WHERE id = :account_id");
            $this->db->bind(':username', $username);
            $this->db->bind(':role', $role); 
            $this->db->bind(':is_deleted', $is_deleted); 
            $this->db->bind(':updated_at', $updatedAt);
            $this->db->bind(':account_id', $account_id);
            $this->db->execute();
        }else{
            $this->db->query("UPDATE tb_accounts SET username = :username, password = :password, updated_at = :updated_at ,role = :role ,is_deleted = :is_deleted  WHERE id = :account_id");
            $this->db->bind(':username', $username);
            $this->db->bind(':password', $hashedPassword);
            $this->db->bind(':role', $role); 
            $this->db->bind(':is_deleted', $is_deleted); 
            $this->db->bind(':updated_at', $updatedAt);
            $this->db->bind(':account_id', $account_id);
            $this->db->execute();
        }
        // Update tb_accounts
        
        
        // Update tb_admin
        $this->db->query("UPDATE tb_admins SET firstname = :firstname, middlename = :middlename, lastname = :lastname, contact = :contact, updated_at = :updated_at WHERE id = :admin_id");
        $this->db->bind(':firstname', $firstname);
        $this->db->bind(':middlename', $middlename);
        $this->db->bind(':lastname', $lastname);
        $this->db->bind(':contact', $contact);
        $this->db->bind(':updated_at', $updatedAt);
        $this->db->bind(':admin_id', $admin_id);
        // Execute
        return $this->db->execute();
    }

    public function updateStatus($data){
        $updatedAt = date('Y-m-d H:i:s');
        $this->db->query("UPDATE tb_admins SET is_deleted = :is_deleted, updated_at = :updated_at WHERE id = :id");
        $this->db->bind(':is_deleted', $data['is_deleted']);
        $this->db->bind(':updated_at', $updatedAt);
        $this->db->bind(':id', $data['admin_id']);

        return $this->db->execute();

    }

}
?>