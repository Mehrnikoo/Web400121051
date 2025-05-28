<?php
class Item {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllItems() {
        try {
            $stmt = $this->db->query("SELECT * FROM items ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            // Handle error (log it, show a message, etc.)
            return [];
        }
    }

    // --- TODO: Add methods here for ---
    // public function getItemById($id) { ... }
    // public function createItem($name, $description, $price) { ... }
    // public function updateItem($id, $name, $description, $price) { ... }
    // public function deleteItem($id) { ... }
}
?>