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
    // public function new() { ... } 
     public function new() {
        // This method's job is just to show the 'new_form.php' view.
        $this->loadView('items/new_form', ['pageTitle' => 'Add New Item']);
    }
    // Show 'add new' form
    // public function create() { ... }  
        public function create() {
        // Check if the form was submitted using POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1. Get data from the form (basic - no validation yet!)
            $name = $_POST['name'] ?? 'No Name';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0.00;

            // 2. Create an Item model instance
            $itemModel = new Item();

            // 3. Call the model's method to save to the database
            $success = $itemModel->createItem($name, $description, $price);

            // 4. Redirect back to the main items list
            // (In a real app, you might show a success/error message here)
            header('Location: /web400121051/items');
            exit(); // Always exit after a header redirect!

        } else {
            // If someone tries to just visit /items/create directly, send them away
            header('Location: /web400121051/items');
            exit();
        }
    }
    // Handle form submission for 'new'
    // public function edit($id) { ... }  
    public function edit($id = 0) { // Called for /items/edit/{id}
    $id = (int)$id;

    if ($id > 0) {
        $itemModel = new Item();
        $item = $itemModel->getItemById($id); // We already built this!

        if ($item) {
            // Item found, load the edit form view, passing the item data
            $this->loadView('items/edit_form', ['item' => $item, 'pageTitle' => 'Edit ' . $item['name']]);
        } else {
            // Item not found
            die("Item not found!");
        }
    } else {
        // No ID
        header('Location: /web400121051/items');
        exit();
    }
} 
    // Show 'edit' form
    // public function update($id) { ... } // Handle form submission for 'edit'
    public function update($id = 0) { // Called for /items/update/{id} via POST
    $id = (int)$id;

    if ($id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {

        // 1. Get the data from the form
        $name = $_POST['name'] ?? 'No Name';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0.00;

        // 2. Use the Model to save the data
        $itemModel = new Item();
        $success = $itemModel->updateItem($id, $name, $description, $price);

        // 3. Redirect back to the items list (or details page)
        header('Location: /web400121051/items');
        exit();

    } else {
        // If no ID or not POST, just send them home/list
        header('Location: /web400121051/items');
        exit();
    }
}

    // public function delete($id) { ... } // Handle deletion

    public function delete($id = 0) { // Called for /items/delete/{id} via POST
    $id = (int)$id;

    // We MUST check it's a POST request for safety
    if ($id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemModel = new Item();
        $success = $itemModel->deleteItem($id);

        // Redirect back to the items list regardless of success for now
        // You could add error/success messages later.
        header('Location: /web400121051/items');
        exit();

    } else {
        // If no ID or not POST, just send them back to the list
        header('Location: /web400121051/items');
        exit();
    }
}

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