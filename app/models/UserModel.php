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
}
?>