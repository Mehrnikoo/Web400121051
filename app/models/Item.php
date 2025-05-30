<?php

namespace App\Models; // <-- ADD THIS

use App\Config\Database; // <-- ADD THIS (Points to the Database class)
use PDO; // <-- ADD THIS
use PDOException; // <-- ADD THIS

class Item {
    private $db;

    public function __construct() {
        // Ensure Database class is loaded (autoloader should handle it)
        $this->db = Database::getInstance()->getConnection();
    }

    // Method to get all items
    public function getAllItems() {
        try {
            $stmt = $this->db->query("SELECT * FROM items ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Database error while fetching items: " . $e->getMessage());
            return []; // Return empty on error
        }
    }

    public function getItemById($id) {
    try {
        $sql = "SELECT * FROM items WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind ID as an integer
        $stmt->execute();
        return $stmt->fetch(); // Use fetch() for a single result (returns false if not found)
    } catch(PDOException $e) {
        error_log("Database error while fetching item ID {$id}: " . $e->getMessage());
        return false; // Return false on error
    }
}

    // Method to create an item
    public function createItem($name, $description, $price) {
        try {
            $sql = "INSERT INTO items (name, description, price) VALUES (:name, :description, :price)";
            $stmt = $this->db->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR); // PDO often treats decimals as strings

            return $stmt->execute(); // Returns true on success

        } catch(PDOException $e) {
            error_log("Database error while creating item: " . $e->getMessage());
            return false; // Return false on error
        }
    }

    public function updateItem($id, $name, $description, $price) {
    try {
        $sql = "UPDATE items SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);

        return $stmt->execute(); // Returns true on success

    } catch(PDOException $e) {
        error_log("Database error while updating item ID {$id}: " . $e->getMessage());
        return false;
    }
}

    public function deleteItem($id) {
        try {
            $sql = "DELETE FROM items WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute(); // Returns true on success
        } catch(PDOException $e) {
            error_log("Database error while deleting item ID {$id}: " . $e->getMessage());
            return false;
        }
    }
    // --- FINNISHED: Add methods here for getItemById, updateItem, deleteItem ---

} // End of Item class
// It's often best practice to NOT have a closing ?>