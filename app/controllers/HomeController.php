<?php
namespace App\Controllers; // <-- ADD THIS

class HomeController extends BaseController { // <-- CHANGED HERE
    // This controller handles the home page and redirects to items
    public function index() {// Check if user is logged in and is an admin
        if (
            isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true &&
            isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'
        ) {
            // If logged in as admin, go to items management
            header('Location: /web400121051/items');
            exit();
        } else {
            // If not logged in, or not an admin, go to login page
            header('Location: /web400121051/auth/login');
            exit();
        }}
    // Helper to load views (can be put in a BaseController later)
    protected function loadView($viewPath, $data = []) {
        extract($data);
        $viewFile = 'app/views/' . $viewPath . '.php';
        if (file_exists($viewFile)) { require_once $viewFile; }
        else { die("View not found: " . $viewFile); }
    }
}
?>