<?php
namespace App\Controllers; // <-- ADD THIS

class HomeController extends BaseController { // <-- CHANGED HERE
    // This controller handles the home page and redirects to items
    public function index() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            // User is logged in
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                // Logged-in Admin: Go to item management
                header('Location: /web400121051/items');
            } else {
                // Logged-in Regular User: Go to the shop
                header('Location: /web400121051/shop');
            }
        } else {
            // User is NOT logged in (first encounter or logged out): Go to the shop
            header('Location: /web400121051/shop');
        }
        exit();
    }
    // Helper to load views (can be put in a BaseController later)
    protected function loadView($viewPath, $data = []) {
        extract($data);
        $viewFile = 'app/views/' . $viewPath . '.php';
        if (file_exists($viewFile)) { require_once $viewFile; }
        else { die("View not found: " . $viewFile); }
    }
}
?>