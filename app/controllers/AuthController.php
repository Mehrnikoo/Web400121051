<?php

namespace App\Controllers;

// We'll need a way to load views. If you have a BaseController with loadView,
// you can extend it. For now, we'll add a simple loadView here.
// Ideally, this loadView method should be in a BaseController.
class AuthController extends BaseController {

    /**
     * Displays the login form.
     * Called for URLs like /auth/login
     */
    public function login() {
        // Any error message can be passed from a failed login attempt (later)
        $data = [
            'pageTitle' => 'Admin Login'
        ];
        $this->loadView('auth/login', $data);
    }

    // --- We will add processLogin() and logout() methods here later ---


    // Temporary loadView method (ideally this is in a BaseController)
    // If your ItemsController's loadView is public, you could try to call it,
    // but it's better to have a shared one.
    protected function loadView($viewPath, $data = []) {
        // Make sure any data passed is available as variables in the view
        extract($data);

        $viewFile = 'app/views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . htmlspecialchars($viewFile));
        }
    }
}
?>