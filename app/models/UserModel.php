<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Finds a user by their username.
     * @param string $username
     * @return mixed The user data as an associative array if found, otherwise false.
     */
    public function findByUsername(string $username) {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(); // Returns user data or false if not found
        } catch (PDOException $e) {
            error_log("Database error in UserModel::findByUsername(): " . $e->getMessage());
            return false;
        }
    }

    // You might add other methods later, like findById(), createUser(), etc.
     public function createUser(string $username, string $password, string $role = 'user') {
        try {
             // ▼▼▼ THIS LINE SECURELY HASHES THE PASSWORD ▼▼▼
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Check for duplicate username error (MySQL error code 1062)
            if ($e->errorInfo[1] == 1062) {
                error_log("UserModel::createUser() - Duplicate username: " . $username);
                return 'duplicate_username'; // Special return value for duplicate username
            }
            error_log("Database error in UserModel::createUser(): " . $e->getMessage());
            return false;
        }
    }
}
?>