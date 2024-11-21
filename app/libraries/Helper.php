<?php
class Helper
{
    // Start the session if not already started
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Check if user is logged in
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    // Check if the logged-in user is an admin
    public static function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Sanitize input to prevent XSS
    public static function sanitizeInput($data)
    {
        return htmlspecialchars(trim($data));
    }

    // Set a flash message in the session
    public static function flashMessage($message, $type = 'success')
    {
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    // Get and clear the flash message
    public static function getFlashMessage()
    {
        if (isset($_SESSION['flash_message'])) {
            $flash = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $flash;
        }
        return null;
    }

    // Redirect to a given URL
    public static function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
    //for Show function in the productsController
    public static function formatProductDetails($product)
    {
        if (!$product) {
            return null;
        }

        return [
            'productName' => $product->productName,
            'brand' => $product->brand,
            'originalPrice' => $product->originalPrice,
            'sellingPrice' => $product->sellingPrice,
            'weight' => $product->weight,
            'categoryName' => $product->categoryName
        ];
    }


    //function for storing all the column names in an array
    public static function getProductFields()
    {
        return ['productName', 'brand', 'originalPrice', 'sellingPrice', 'weight', 'categoryId'];
    }

    //function for storing all the column names in an array for the usercontroller
    public static function setSessionUser($user)
    {
        // Store user information in the session as an array
        $_SESSION['user'] = [
            'name' => $user->name,
            'user_id' => $user->id,
            'role' => $user->role,
        ];
    }

    public static function getSessionUser()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public static function logout()
    {
        unset($_SESSION["user"]);
        session_destroy();
    }
}
