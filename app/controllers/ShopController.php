<?php

namespace App\Controllers;

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
                $this->loadView('shop/show_details', [
                    'pageTitle' => htmlspecialchars($item['name']) . ' - Shop',
                    'item' => $item
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