<?php
class HomeController {
    public function index() {
        // Maybe redirect to items or show a welcome page
        header('Location: /online_shop/items');
        exit();
        // Or: $this->loadView('home/index', ['pageTitle' => 'Welcome!']);
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