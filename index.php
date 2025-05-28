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


if ($parts[0] == 'web400121051') {
    // This is the base folder for your project, so we remove it
    // to simplify the routing logic.
    array_shift($parts);
}

$controller_name = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
$action_name = !empty($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// Default to HomeController if the controller doesn't exist
// ... after $params = array_slice($parts, 2);

$controller_name = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
$action_name = !empty($parts[1]) ? $parts[1] : 'index';
$params = array_slice($parts, 2);

// --- ADD DEBUGGING HERE ---
echo "Attempting to load Controller: " . $controller_name . "<br>";
echo "Attempting to load Action: " . $action_name . "<br>";
$controller_file_path = 'app/controllers/' . $controller_name . '.php';
echo "Looking for file: " . $controller_file_path . "<br>";
echo "Does file exist? ";
var_dump(file_exists($controller_file_path)); // This shows true or false
echo "Does class exist (before trying)? ";
var_dump(class_exists($controller_name, false)); // Check without autoloading yet
echo "Does class exist (with autoload)? ";
var_dump(class_exists($controller_name)); // This *tries* to load it
echo "<hr>";
// --- END DEBUGGING ---


// Default to HomeController if the controller doesn't exist
if (!class_exists($controller_name)) {
    // --- ADD DEBUGGING HERE TOO ---
    echo ">>> FAILED to find " . $controller_name . "! Falling back to HomeController. <<< <br>";
    // --- END DEBUGGING ---
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