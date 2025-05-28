<?php
class ItemsController {

    public function index() { // This will be called for /items or /items/index
        $itemModel = new Item();
        $items = $itemModel->getAllItems();

        // Pass data to the view
        $this->loadView('items/list', ['items' => $items, 'pageTitle' => 'Our Stock']);
    }

    // --- TODO: Add methods here for ---
    // public function show($id) { ... }    // Show one item
    // public function new() { ... }       // Show 'add new' form
    // public function create() { ... }    // Handle form submission for 'new'
    // public function edit($id) { ... }   // Show 'edit' form
    // public function update($id) { ... } // Handle form submission for 'edit'
    // public function delete($id) { ... } // Handle deletion

    /**
     * Helper function to load views.
     * @param string $viewPath Path to the view file (e.g., 'items/list')
     * @param array $data Data to pass to the view
     */
    protected function loadView($viewPath, $data = []) {
        // Extract data so it's available as variables in the view
        extract($data);

        $viewFile = 'app/views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $viewFile);
        }
    }
}
?>