<?php
// Autoload classes (basic example)
spl_autoload_register(function ($class_name) {
    $paths = ['app/controllers/', 'app/models/', 'config/'];
    foreach ($paths as $path) {
        $file = $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


// Basic Routing (using GET parameters)
//$controller_name = !empty($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'HomeController';
//$action_name = !empty($_GET['action']) ? $_GET['action'] : 'index';
// Note: Handling parameters (like an ID) gets a bit more complex here.

// ... rest of the controller loading/calling logic ...

// Basic Routing
$request_uri = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $request_uri);

// Remove the base folder 'online_shop' if present
if ($parts[0] == 'online_shop') {
    array_shift($parts);
}

$controller_name = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
$action_name = !empty($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// Default to HomeController if the controller doesn't exist
if (!class_exists($controller_name)) {
    $controller_name = 'HomeController';
    $action_name = 'index'; // Or an error page action
    $params = [];
}

// Instantiate the controller
$controller = new $controller_name();

// Call the action method if it exists
if (method_exists($controller, $action_name)) {
    call_user_func_array([$controller, $action_name], $params);
} else {
    // Handle 404 Not Found - A simple way for now:
    require_once 'app/views/404.php';
}
?>