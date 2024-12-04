<?php
// Include the bootstrap file to initialize the app
require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Instantiate necessary models
$orderModel = new OrderModel();
$productModel = new Product();
$mailController = new MailController(); // Assuming you have a MailController for sending emails

// Get orders that are pending and older than 1 minute
$orders = $orderModel->getPendingOrdersOlderThan(1); // Adjusted the time limit to 1 minute

foreach ($orders as $order) {
    // Get the associated cart items for the order
    $cartItems = $orderModel->getOrderItemsFromCart($order->id);

    // Check if cart items are empty, if so, skip the current order
    if (empty($cartItems)) {
        echo "No cart items found for order ID: " . $order->id;
        continue;
    }

    // Loop through each cart item and increase the stock by the quantity
    foreach ($cartItems as $item) {
        $product_id = $item->productId;
        // Get the quantity from the carts table for the specific product and order
        $quantityInCart = $productModel->getProductQuantityFromCart($product_id, $order->id);
        // $quantityInCart = 5;
        // Restock the product in the inventory by increasing the stock
        $productModel->increaseStockByCartQuantity($product_id, $quantityInCart);
    }


    // Update the order status to "canceled"
    $orderModel->updateOrderStatus($order->id, 'canceled');

    // Log out the user (if logged in)
    if (isset($_SESSION['user_id'])) {
        // Create an instance of UserController and call the logout method
        $userController = new UserController();
        $userController->logout();  // Calls the logout method from UserController

        // Exit after logging out (logout logic handles redirection)
        exit();
    }

    // Get the user associated with the order and send the cancellation email
    $user = $orderModel->getUserByOrderId($order->id);  // Get user info based on the order

    if ($user) {
        // Get full order details for the email (including cart items)
        $orderDetails = $orderModel->getOrderById($order->id);

        // Prepare the email subject
        $subject = "Your Order has been Canceled";

        // Send the cancellation email to the user
        $mailController->sendEmail(
            $user->email,
            $subject,
            $user->name,
            $orderDetails->id,
            $orderDetails->totalAmount, // Send total amount if needed
            $cartItems // Send cart items for cancellation notification
        );
    }
}
