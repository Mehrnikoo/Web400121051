<?php

namespace App\Controllers;
use App\Models\CommentModel;

use App\Models\Item; // We need to fetch items

class ShopController extends BaseController {

    // public function __construct() {
    //     // Protect the whole shop controller: user must be logged in
    //     if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) {
    //         $_SESSION['login_error'] = 'You must be logged in to view the shop.';
    //         header('Location: /web400121051/auth/login');
    //         exit();
    //     }
    // }
    // Inside ShopController class
public function addComment($itemId = 0) {
    $itemId = (int)$itemId;

    // 1. Check if user is logged in
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) {
        // Should not happen if form is hidden, but good for direct access protection
        $_SESSION['login_error'] = 'You must be logged in to comment.';
        header('Location: /web400121051/auth/login');
        exit();
    }

    // 2. Check if it's a POST request and item ID is valid
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $itemId > 0) {
        $commentText = trim($_POST['comment_text'] ?? '');
        $userId = $_SESSION['user_id']; // Get user ID from session

        // 3. Basic validation
        if (empty($commentText)) {
            // Handle empty comment error - maybe set a session flash message
            // For now, just redirect back
            $_SESSION['comment_form_error'] = "Comment cannot be empty."; // We'll need to display this
            header('Location: /web400121051/shop/show/' . $itemId);
            exit();
        }

        // 4. Add comment using the model
        $commentModel = new CommentModel();
        $success = $commentModel->addComment($itemId, $userId, $commentText);

        if ($success) {
            // Optional: Set a success flash message
            // $_SESSION['comment_form_success'] = "Comment posted successfully!";
        } else {
            // Optional: Set an error flash message
            // $_SESSION['comment_form_error'] = "Failed to post comment. Please try again.";
        }

        // 5. Redirect back to the item detail page to see the new comment
        header('Location: /web400121051/shop/show/' . $itemId);
        exit();

    } else {
        // Not a POST request or invalid item ID, redirect to shop or item page
        if ($itemId > 0) {
            header('Location: /web400121051/shop/show/' . $itemId);
        } else {
            header('Location: /web400121051/shop');
        }
        exit();
    }
}


    public function index() { // This is called for /shop or /shop/index
        $itemModel = new Item();
        $items = $itemModel->getAllItems();

        $this->loadView('shop/index', [
            'pageTitle' => 'Welcome to Our Shop!',
            'items' => $items
        ]);
    }

    public function show($id = 0) {
        $id = (int)$id;

        if ($id > 0) {
            $itemModel = new Item();
            $item = $itemModel->getItemById($id);

            if ($item) {
// --- NEW: Fetch comments ---
            $commentModel = new CommentModel();
            $comments = $commentModel->getCommentsByItemId($id);
            // --- END NEW ---

    // --- DEBUG START ---
    //echo "Debug: Comments fetched in controller:<pre>";
    //var_dump($comments);
    //echo "</pre>";
    // You can add a die() here if you want to stop execution and only see this:
    // die("Stopped after fetching comments in controller."); 
    // --- DEBUG END ---
            $this->loadView('shop/show_details', [
                'pageTitle' => htmlspecialchars($item['name']) . ' - Shop',
                'item' => $item,
                'comments' => $comments // <-- Pass comments to the view
            
                ]);
            } else {
                // Item not found, show our 404 view
                http_response_code(404);
                // Note: __DIR__ ensures path is relative to current file (ShopController.php)
                $view_404 = __DIR__ . '/../views/404.php';
                if (file_exists($view_404)) {
                    require_once $view_404;
                } else {
                    die("Error: Item not found and the main 404 page is also missing!");
                }
                exit();
            }
        } else {
            // No valid ID, redirect to the main shop page
            header('Location: /web400121051/shop');
            exit();
        }
    }
    // ▲▲▲ END OF THE show() METHOD ▲▲▲


    // Inside the ShopController class
    public function forYou() {
        // This page is for logged-in users only
        if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) {
            $_SESSION['login_error'] = 'You must be logged in to see your "For You" page.';
            header('Location: /web400121051/auth/login');
            exit();
        }

        $itemModel = new Item();
        $randomItems = $itemModel->getRandomItems(3); // Get 3 random items

        $this->loadView('shop/foryou', [
            'pageTitle' => 'Just For You!',
            'items' => $randomItems // Pass items with the same variable name as shop/index
        ]);
        }
        
}
?>