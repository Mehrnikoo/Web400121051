<?php

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

    // --- TODO: Add methods here for getItemById, updateItem, deleteItem ---

} // End of Item class
// It's often best practice to NOT have a closing ?>