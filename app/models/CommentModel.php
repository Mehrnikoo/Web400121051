<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class CommentModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

        /**
     * Deletes a specific comment by its ID.
     *
     * @param int $commentId The ID of the comment to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteCommentById(int $commentId): bool {
        try {
            $sql = "DELETE FROM comments WHERE id = :comment_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error in CommentModel::deleteCommentById(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches all comments for a specific item, along with the commenter's username.
     * Orders comments by oldest first.
     *
     * @param int $itemId The ID of the item.
     * @return array An array of comments.
     */
    public function getCommentsByItemId(int $itemId): array {
        try {
            $sql = "SELECT c.*, u.username 
                    FROM comments c
                    JOIN users u ON c.user_id = u.id
                    WHERE c.item_id = :item_id
                    ORDER BY c.created_at ASC"; // Show oldest comments first
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database error in CommentModel::getCommentsByItemId(): " . $e->getMessage());
            return []; // Return empty array on error
        }
    }

    /**
     * Adds a new comment to the database.
     *
     * @param int $itemId The ID of the item being commented on.
     * @param int $userId The ID of the user posting the comment.
     * @param string $commentText The text of the comment.
     * @return bool True on success, false on failure.
     */
    public function addComment(int $itemId, int $userId, string $commentText): bool {
        try {
            $sql = "INSERT INTO comments (item_id, user_id, comment_text) 
                    VALUES (:item_id, :user_id, :comment_text)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':comment_text', $commentText, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database error in CommentModel::addComment(): " . $e->getMessage());
            return false;
        }
    }
}
?>