<?php

namespace App\Controllers;

class BaseController {

    /**
     * Helper function to load views.
     * Makes data available as variables in the view file.
     *
     * @param string $viewPath Path to the view file relative to 'app/views/' (e.g., 'items/list', 'auth/login')
     * @param array $data Data to pass to the view (optional)
     */
    protected function loadView($viewPath, $data = []) {
        // Make sure any data passed is available as variables in the view
        extract($data);

        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        // Note: Changed path to be relative from this BaseController's directory for robustness.
        // __DIR__ gives the directory of the current file (BaseController.php)

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . htmlspecialchars($viewFile));
        }
    }
}
?>