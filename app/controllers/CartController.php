<?php

class CartController extends Controller
{
    private $cartModel;

    public function __construct()
    {
        error_log("Cart page opening");
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/UserController/login'); // Redirect to login if not logged in
            exit;
        }
        $this->cartModel = $this->model("CartModel");
    }

    public function index()
    {
        error_log("Cart index page opening");
        $userId = $_SESSION['user_id'];

        // Fetch cart items with associated product images
        $cartItems = $this->cartModel->getCartItemsWithImages($userId);

        // Prepare data to pass to the view
        $data = [
            'cartItems' => $cartItems,
        ];

        // Check for success or error messages from the session
        if (isset($_SESSION['message'])) {
            $data['message'] = $_SESSION['message'];
            unset($_SESSION['message']); // Clear message after displaying
        }

        if (isset($_SESSION['error'])) {
            $data['error'] = $_SESSION['error'];
            unset($_SESSION['error']); // Clear error after displaying
        }

        // Display the cart items in the cart/index view
        $this->view('cart/index', $data);
    }



    // Method to add items to the cart
    // In CartController
    // In CartController
    // In CartController
    public function addToCart()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['productId'])) {
                $productId = $_POST['productId'];

                // Add to cart in the database
                $result = $this->cartModel->addToCart($userId, $productId);

                // Set success or error message in the session
                if ($result['status'] === 'success') {
                    $_SESSION['message'] = $result['message']; // Success message
                    $_SESSION['message_time'] = time(); // Set the timestamp
                    // $data=['cartProducts'=>$productId] = $productId; // Track added products
                } else {
                    $_SESSION['error'] = $result['message']; // Error message
                }
            } else {
                $_SESSION['error'] = 'Invalid product ID';
            }
        }

        // Redirect back to the product index page after processing
        header('Location: ' . URLROOT . '/products');
        exit;
    }


    public function update()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['productId'];
            $action = $_POST['action'];

            // Get the current quantity from the database
            $currentItem = $this->cartModel->getCartItems($userId, $productId);

            if ($currentItem) {
                $currentQuantity = $currentItem->quantity;

                // Determine new quantity based on action
                $newQuantity = ($action === 'increase') ? $currentQuantity + 1 : $currentQuantity - 1;

                // Update or remove item based on new quantity
                if ($newQuantity > 0) {
                    $result = $this->cartModel->updateCart($userId, $productId, $newQuantity);
                } else {
                    $result = $this->cartModel->removeFromCart($userId, $productId);
                }
            } else {
                $_SESSION['error'] = 'Item not found in cart.';
            }

            // Redirect back to the cart page
            header('Location: ' . URLROOT . '/CartController/index');
            exit;
        }
    }
    public function removeItem()
    {
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['productId'];

            // Call the model to remove the item
            $result = $this->cartModel->removeItem($userId, $productId);

            // Set success or error message
            if ($result) {
                $_SESSION['message'] = 'Item removed from cart successfully.';
            } else {
                $_SESSION['error'] = 'Failed to remove item from cart.';
            }

            // Redirect to the cart page
            header('Location: ' . URLROOT . '/CartController/index');
            exit;
        }
    }
}
