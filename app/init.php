<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load the configuration
require_once __DIR__ . '/config/config.php';

// Autoload Core Libraries and Models
spl_autoload_register(function ($className) {
    // Check for the class in the libraries directory
    $filePath = APPROOT . '/libraries/' . $className . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Check for the class in the models directory
        $filePath = APPROOT . '/models/' . $className . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            die("The class {$className} could not be loaded.");
        }
    }
});

// Helper Functions (optional, if you have helper functions)
$helperPath = APPROOT . '/helpers/helper.php';
if (file_exists($helperPath)) {
    require_once $helperPath;
}

// Set up database connection (if you want to centralize this in init.php)
// Example of initializing the Database class
// $database = new Database();

// Now your app is ready, and any script that requires initialization can include this file
