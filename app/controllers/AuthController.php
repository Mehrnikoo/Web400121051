<?php

namespace App\Controllers;

use App\Models\UserModel; // <-- ADD THIS LINE

// We'll need a way to load views. If you have a BaseController with loadView,
// you can extend it. For now, we'll add a simple loadView here.
// Ideally, this loadView method should be in a BaseController.
class AuthController extends BaseController {

    /**
     * Displays the login form.
     * Called for URLs like /auth/login
     */
    public function login() {

    $error = $_SESSION['login_error'] ?? null;

    unset($_SESSION['login_error']);


    $data = [
        'pageTitle' => 'Admin Login',
        'error' => $error
    ];
    $this->loadView('auth/login', $data);
    }

    // --- We will add processLogin() and logout() methods here later ---
        // --- NEW METHOD ---
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['login_error'] = 'Username and password are required.';
                header('Location: /web400121051/auth/login');
                exit();
            }

            $userModel = new UserModel();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Password is correct, login successful!
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                // Redirect to a protected page (e.g., the items list or an admin dashboard)
                header('Location: /web400121051/items'); // Or an admin dashboard page
                exit();
            } else {
                // Invalid credentials
                $_SESSION['login_error'] = 'Invalid username or password.';





                header('Location: /web400121051/auth/login');
                exit();
            }
        } else {
            // Not a POST request, redirect to login
            header('Location: /web400121051/auth/login');
            exit();
        }
    }
    // --- END OF NEW METHOD ---

   public function logout() {
    // Unset all of the session variables
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();

    // Redirect to the login page
    header('Location: /web400121051/auth/login');
    exit();
    }
}
?>