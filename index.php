<?php

// For development: display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Include Composer's Autoloader
// This single line handles loading all your namespaced classes!
require_once __DIR__ . '/vendor/autoload.php';

// --- Old spl_autoload_register function is now DELETED ---

// Basic Routing
$request_uri = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $request_uri);

// Remove the base folder 'web400121051' if present
if (isset($parts[0]) && $parts[0] == 'web400121051') {
    array_shift($parts);
}

// Determine controller and action
$controller_slug = !empty($parts[0]) ? $parts[0] : 'home'; // e.g., 'home' or 'items'
$action_name = !empty($parts[1]) ? $parts[1] : 'index';    // e.g., 'index' or 'show' or 'new'
$params = array_slice($parts, 2);                          // e.g., ['1'] for an ID

// 2. Construct the Fully Qualified Controller Class Name
$controller_class = 'App\\Controllers\\' . ucfirst($controller_slug) . 'Controller';
$default_controller_class = 'App\\Controllers\\HomeController';

// Check if the determined controller class exists
if (!class_exists($controller_class)) {
    // If not, try to default to HomeController
    if (class_exists($default_controller_class)) {
        $controller_class = $default_controller_class;
        // If we default to HomeController, assume 'index' action unless specified
        // This logic might need adjustment if Home has actions other than index
        $action_name = !empty($parts[0]) && $parts[0] !== 'home' ? $parts[0] : 'index';
        $params = array_slice($parts, 1);
    } else {
        // If even HomeController doesn't exist (major problem)
        http_response_code(500);
        die("Critical Error: Default controller not found.");
    }
}

// Instantiate the controller
$controller = new $controller_class();

// Call the action method if it exists
if (method_exists($controller, $action_name)) {
    call_user_func_array([$controller, $action_name], $params);
} else {
    // If action doesn't exist in the controller, show 404
    http_response_code(404);
    $view_404 = 'app/views/404.php';
    if (file_exists($view_404)) {
        require_once $view_404;
    } else {
        die("Error: Action not found and 404 page is missing!");
    }
}
?>