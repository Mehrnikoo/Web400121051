<?php
class HomeController {
    public function index() {
    echo "<h1>Welcome to the Home Page!</h1>";
    header('Location: /web400121051/items');
    exit();
    //echo "<p>If you see this, HomeController is working.</p>";
    //echo '<p><a href="/web400121051/items">Try going to Items</a></p>';
    // We REMOVED the header('Location: ...') and exit();
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