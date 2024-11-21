<?php
session_start();
require_once 'config/config.php';
require_once '../vendor/autoload.php';

spl_autoload_register(function ($className) {
    require_once 'libraries/' . $className . '.php';
});

// Simple Router
// Add these lines at the top of bootstrap.php
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];


if ($requestUri == '/register' && $requestMethod == 'GET') {
    $controller = new UserController();
    $controller->register();
} elseif ($requestUri == '/login' && $requestMethod == 'GET') {
    $controller = new UserController();
    $controller->login();
} elseif ($requestUri == '/logout' && $requestMethod == 'POST') {
    $controller = new UserController();
    $controller->logout();
} else {
    // 404 Page
    //echo "404 Not Found";
}
