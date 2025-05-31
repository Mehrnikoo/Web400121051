<?php
namespace App\Controllers; // <-- ADD THIS

use App\Models\Item; // <-- ADD THIS (Points to the Item class)

use App\Controllers\BaseController; // <-- ADD THIS (Points to the BaseController class)

class ItemsController extends BaseController { // <-- CHANGED HERE

 // --- ADD THIS CONSTRUCTOR ---
    public function __construct() {
        // Call parent constructor if BaseController has one (optional for now as ours doesn't)
        // parent::__construct(); 

        // Check if user is logged in and is an admin
        // Make sure session_start() is called in index.php!
        if (
            !(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true &&
              isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')
        ) {
            // If not logged in as admin, redirect to login page
            $_SESSION['login_error'] = 'You must be logged in as an admin to access this page.';
            header('Location: /web400121051/auth/login');
            exit();
        }
    }
    // --- END OF CONSTRUCTOR ---

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
     * Displays the details for a single item.
     * Called for URLs like /items/show/{id}
     *
     * @param int $id The ID of the item to show.
     */
    public function show($id = 0) {
        $id = (int)$id; // Make sure the ID is treated as a number

        // Check if a valid ID was provided
        if ($id > 0) {
            // Create an instance of our Item model
            $itemModel = new Item();
            // Try to fetch the item by its ID (we already added this method to Item.php)
            $item = $itemModel->getItemById($id);

            // Check if an item was actually found
            if ($item) {
                // If we found the item, load the 'show_details' view
                // Pass the item data and set the page title
                $this->loadView('items/show_details', ['item' => $item, 'pageTitle' => $item['name']]);
            } else {
                // If no item was found with that ID, show a 404 error
                // We'll try loading our new 404.php view
                // We need to make sure 'loadView' can handle 404 or use require_once directly
                if (file_exists('app/views/404.php')) {
                   http_response_code(404); // Set the 404 status code
                   require_once 'app/views/404.php';
                } else {
                   die("Error: Item not found and 404 page is missing!");
                }
                exit();
            }
        } else {
            // If no ID (or an invalid ID) was given in the URL,
            // just redirect back to the main items list.
            header('Location: /web400121051/items');
            exit();
        }
    }

   
}
?>