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
// Inside ItemsController.php, in the ItemsController class:
public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? 'No Name';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? 0.00;
        $imageFilenameToSave = null; // Variable to hold the filename to save to DB

        // --- Image Upload Handling ---
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {

            // Define the target directory relative to the project root for consistency
            // __DIR__ is the directory of ItemsController.php (app/controllers)
            // So we go up two levels to the project root, then to public/uploads/items
            $uploadTargetDir = dirname(__DIR__, 2) . '/public/uploads/items/';

            // Ensure the directory exists and is writable (we created it, but good to check)
            if (!is_dir($uploadTargetDir)) {
                // Try to create it if it somehow got deleted (won't fix permissions)
                if (!mkdir($uploadTargetDir, 0775, true) && !is_dir($uploadTargetDir)) {
                     error_log('Failed to create upload directory: ' . $uploadTargetDir);
                     // Potentially set a user error message here too
                     // For now, we'll proceed without an image
                }
            }

            if (is_writable($uploadTargetDir)) {
                $originalFileName = basename($_FILES['item_image']['name']);
                $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

                // Create a unique filename to prevent overwrites and for basic sanitization
                // Replace non-alphanumeric (except ., -, _) with nothing from the base filename
                $safeBaseName = preg_replace("/[^a-zA-Z0-9\-_]/", "", pathinfo($originalFileName, PATHINFO_FILENAME));
                if(empty($safeBaseName)) { $safeBaseName = 'image'; } // handle empty base name after sanitizing
                $uniqueFileName = time() . '_' . $safeBaseName . '.' . $fileExtension;
                $targetFilePath = $uploadTargetDir . $uniqueFileName;

                // Allowed file types
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExtension, $allowedTypes)) {
                    if (move_uploaded_file($_FILES['item_image']['tmp_name'], $targetFilePath)) {
                        $imageFilenameToSave = $uniqueFileName; // Save only the unique filename
                    } else {
                        error_log("Failed to move uploaded file. Target: " . $targetFilePath . ". Check permissions and path.");
                        $_SESSION['form_error'] = "Sorry, there was an error uploading your image (could not move file).";
                    }
                } else {
                    error_log("Invalid file type: " . $originalFileName . ". Allowed types: " . implode(', ', $allowedTypes));
                    $_SESSION['form_error'] = "Invalid file type. Allowed: " . implode(', ', $allowedTypes) . ".";
                }
            } else {
                 error_log('Upload directory is not writable: ' . $uploadTargetDir);
                 $_SESSION['form_error'] = "Server error: Upload directory not writable.";
            }

        } elseif (isset($_FILES['item_image']) && $_FILES['item_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Handle other specific PHP upload errors
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => "File exceeds upload_max_filesize.",
                UPLOAD_ERR_FORM_SIZE  => "File exceeds MAX_FILE_SIZE in form.",
                UPLOAD_ERR_PARTIAL    => "File was only partially uploaded.",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload."
            ];
            $errorCode = $_FILES['item_image']['error'];
            $errorMessage = $uploadErrors[$errorCode] ?? "Unknown upload error.";
            error_log("File upload error: " . $errorMessage . " (Code: $errorCode)");
            $_SESSION['form_error'] = "Sorry, there was an error uploading your image: " . $errorMessage;
        }
        // --- End Image Upload Handling ---

        // If there was an image upload error that we want to show to the user, redirect back
        if (isset($_SESSION['form_error'])) {
            // Optional: Store form data in session to re-populate the form
            // $_SESSION['form_data'] = $_POST; 
            header('Location: /web400121051/items/new');
            exit();
        }

        $itemModel = new Item();
        $success = $itemModel->createItem($name, $description, $price, $imageFilenameToSave);

        if ($success) {
            // Optional: Set a success message
            $_SESSION['form_success'] = "Item created successfully!";
        } else {
            // Optional: Set a generic error if DB save failed
            $_SESSION['form_error'] = "Failed to save item to database.";
            // If saving to DB fails and an image was uploaded, you might want to delete the uploaded image file here.
        }

        header('Location: /web400121051/items');
        exit();

    } else {
        // Not a POST request, redirect to the form
        header('Location: /web400121051/items/new');
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
    // Inside ItemsController.php
public function update($id = 0) {
    $id = (int)$id;
    if (!$id) {
        header('Location: /web400121051/items');
        exit();
    }

    $itemModel = new Item();
    $currentItem = $itemModel->getItemById($id); // Get current item data

    if (!$currentItem) {
        die("Item not found for update."); // Or redirect to a 404
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? $currentItem['name']; // Default to current if not set
        $description = $_POST['description'] ?? $currentItem['description'];
        $price = $_POST['price'] ?? $currentItem['price'];

        $imageFilenameToUpdate = $currentItem['image_filename']; // Start with the current image filename

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/items/';

        // 1. Check if "Remove Image" checkbox is checked
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if (!empty($currentItem['image_filename'])) {
                $oldFilePath = $uploadDir . $currentItem['image_filename'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }
            }
            $imageFilenameToUpdate = null; // Set to null for DB
        }

        // 2. Check if a new image is uploaded (this overrides removal if both are present)
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image first if it exists and is different or if we are replacing
            if (!empty($currentItem['image_filename'])) {
                $oldFilePath = $uploadDir . $currentItem['image_filename'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Process the new file (similar to create() method)
            $originalFileName = basename($_FILES['item_image']['name']);
            $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            $safeBaseName = preg_replace("/[^a-zA-Z0-9\-_]/", "", pathinfo($originalFileName, PATHINFO_FILENAME));
            if(empty($safeBaseName)) { $safeBaseName = 'image'; }
            $uniqueFileName = time() . '_' . $safeBaseName . '.' . $fileExtension;
            $targetFilePath = $uploadDir . $uniqueFileName;

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExtension, $allowedTypes)) {
                if (move_uploaded_file($_FILES['item_image']['tmp_name'], $targetFilePath)) {
                    $imageFilenameToUpdate = $uniqueFileName;
                } else {
                    error_log("Update: Failed to move uploaded file. Target: " . $targetFilePath);
                    $_SESSION['form_error'] = "Error uploading new image (could not move file).";
                    // Decide if you want to proceed with other updates or redirect
                }
            } else {
                error_log("Update: Invalid file type: " . $originalFileName);
                $_SESSION['form_error'] = "Invalid new file type. Allowed: " . implode(', ', $allowedTypes);
            }
        }

        // If there was an image related error that should stop the update
        if (isset($_SESSION['form_error'])) {
            header('Location: /web400121051/items/edit/' . $id);
            exit();
        }

        // 3. Update the database
        $success = $itemModel->updateItem($id, $name, $description, $price, $imageFilenameToUpdate);

        // Redirect back
        header('Location: /web400121051/items');
        exit();

    } else {
        // If not POST, it means we are just showing the edit form (handled by edit() method)
        // This part of update() is only for processing the POST request.
        // So, if someone tries to GET /items/update/id, redirect them.
        header('Location: /web400121051/items/edit/' . $id);
        exit();
    }
}

public function delete($id = 0) {
    $id = (int)$id;

    if ($id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemModel = new Item();

        // 1. Fetch the item to get its image filename BEFORE deleting from DB
        $itemToDelete = $itemModel->getItemById($id);

        if ($itemToDelete) {
            // 2. Attempt to delete the item from the database
            $dbDeleteSuccess = $itemModel->deleteItem($id);

            if ($dbDeleteSuccess) {
                // 3. If DB deletion was successful, and there was an image, delete the image file
                if (!empty($itemToDelete['image_filename'])) {
                    $uploadDir = dirname(__DIR__, 2) . '/public/uploads/items/';
                    $filePath = $uploadDir . $itemToDelete['image_filename'];
                    if (file_exists($filePath)) {
                        if (unlink($filePath)) {
                            // Image file deleted successfully
                            error_log("Successfully deleted image file: " . $filePath);
                        } else {
                            // Failed to delete image file (log this, permissions?)
                            error_log("Failed to delete image file: " . $filePath);
                        }
                    } else {
                        error_log("Image file not found for deletion (already gone?): " . $filePath);
                    }
                }
            } else {
                // DB deletion failed, maybe set an error message
                error_log("Failed to delete item ID {$id} from database.");
                // You might want to set a session error message here
            }
        } else {
            error_log("Attempted to delete non-existent item ID {$id}.");
            // You might want to set a session error message here
        }

        // Redirect back to the items list
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