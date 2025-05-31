<?php

namespace App\Controllers;

use App\Models\UserModel; // <-- ADD THIS LINE

// We'll need a way to load views. If you have a BaseController with loadView,
// you can extend it. For now, we'll add a simple loadView here.
// Ideally, this loadView method should be in a BaseController.
class AuthController extends BaseController {

    // Inside AuthController class

    public function register() {
        $error = $_SESSION['register_error'] ?? null;
        $success = $_SESSION['register_success'] ?? null;
        unset($_SESSION['register_error'], $_SESSION['register_success']);

        $data = [
            'pageTitle' => 'Register Account',
            'error' => $error,
            'success' => $success
        ];
        $this->loadView('auth/register', $data);
    }

    public function processRegistration() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Basic Validation
            if (empty($username) || empty($password) || empty($password_confirm)) {
                $_SESSION['register_error'] = 'All fields are required.';
                header('Location: /web400121051/auth/register');
                exit();
            }

            if (strlen($password) < 6) { // Example: minimum password length
                $_SESSION['register_error'] = 'Password must be at least 6 characters long.';
                header('Location: /web400121051/auth/register');
                exit();
            }

            if ($password !== $password_confirm) {
                $_SESSION['register_error'] = 'Passwords do not match.';
                header('Location: /web400121051/auth/register');
                exit();
            }

            $userModel = new UserModel();
            $result = $userModel->createUser($username, $password); // Default role is 'user'

            if ($result === true) {
                $_SESSION['register_success'] = 'Registration successful! You can now log in.';
                header('Location: /web400121051/auth/login'); // Redirect to login after successful registration
                exit();
            } elseif ($result === 'duplicate_username') {
                $_SESSION['register_error'] = 'Username already taken. Please choose another.';
            } else {
                $_SESSION['register_error'] = 'An error occurred during registration. Please try again.';
            }
            header('Location: /web400121051/auth/register');
            exit();

        } else {
            header('Location: /web400121051/auth/register');
            exit();
        }
    }

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
                session_regenerate_id(true);

                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                // Role-based redirect
                if ($user['role'] === 'admin') {
                    header('Location: /web400121051/items'); // Admin goes to item management
                } else {
                    header('Location: /web400121051/shop'); // Regular user goes to shop
                }
                exit();
            } else {
                // Invalid credentials
                $_SESSION['login_error'] = 'Invalid username or password.';
                header('Location: /web400121051/auth/login');
                exit();
            }
        } else {
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