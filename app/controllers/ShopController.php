<?php

namespace App\Controllers;

use App\Models\Item; // We need to fetch items

class ShopController extends BaseController {

    public function __construct() {
        // Protect the whole shop controller: user must be logged in
        if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)) {
            $_SESSION['login_error'] = 'You must be logged in to view the shop.';
            header('Location: /web400121051/auth/login');
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
}
?>