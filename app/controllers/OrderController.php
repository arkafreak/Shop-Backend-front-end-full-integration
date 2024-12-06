<?php


class OrderController extends Controller
{
    private $orderModel;
    private $cartModel;
    private $mailController;
    private $stripeServices;
    private $orderHistoryModel;

    private $productModel;

    public function __construct()
    {
        // Ensure the user is logged in
        Helper::startSession();
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        $this->orderModel = $this->model('OrderModel');
        $this->cartModel = $this->model('CartModel');
        $this->orderHistoryModel = $this->model('OrderHistoryModel');
        $this->productModel = $this->model('Product');
        // Instantiate MailController
        $this->mailController = new MailController();
        $this->stripeServices = new StripeService();
    }

    public function addressPayment()
    {
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getCartItems($userId);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->sellingPrice * $item->quantity;
        }

        // Create an order with paymentMethod as NULL
        $orderId = $this->orderModel->createOrder($userId, $totalAmount, 'pending', null);

        // Reduce stock for each product
        foreach ($cartItems as $item) {
            $this->productModel->reduceStock($item->id, $item->quantity);
        }
        // After creating the order, update cart items with the order ID
        $orderIdNumber = $this->orderModel->getLatestOrderIdByUserId($userId);
        $userId = $_SESSION['user_id'];  // Assuming the user is logged in
        $this->cartModel->updateCartWithOrderId($orderIdNumber, $userId);
        // Pass data to the view
        $data = [
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
            'orderId' => $orderId,
        ];

        $this->view('order/address_payment', $data);
    }

    public function confirm()
    {
        // Ensure the user is logged in
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        $userId = $_SESSION['user_id'];
        $paymentMethod = htmlspecialchars($_POST['paymentMethod']); // Get the payment method from the form

        // Get the latest order created by the user
        $orderId = $this->orderModel->getLatestOrderIdByUserId($userId);

        if (!$orderId) {
            echo "No order found to confirm.";
            return;
        }

        // Update the order with the selected payment method
        $this->orderModel->updateOrderPaymentMethod($orderId, $paymentMethod);

        // Get cart items for the user
        $cartItems = $this->cartModel->getCartItems($userId);

        $outOfStock = false; // Flag to track if any item is out of stock
        foreach ($cartItems as $item) {
            // Check the availability of the product in the products table
            $product = $this->productModel->getProductById($item->productId);

            // If quantity in cart exceeds available stock
            if ($product && $item->quantity > $product->quantity) {
                $outOfStock = true;
                // Show message indicating the product is out of stock
                echo "The item '{$product->productName}' is no longer in stock. ";
            }
        }

        // If any product was out of stock, stop the order confirmation
        if ($outOfStock) {
            echo "Please adjust your cart and try again.";
            return;
        }

        // Calculate total amount (optional, you can reuse if needed)
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item->sellingPrice * $item->quantity;
        }

        // // After creating the order, update cart items with the order ID
        // $userId = $_SESSION['user_id'];  // Assuming the user is logged in
        // $this->cartModel->updateCartWithOrderId($orderId, $userId);


        // Proceed with the payment based on selected method
        if ($paymentMethod === 'paypal') {
            $this->view('paypal/index');
        } elseif ($paymentMethod === 'stripe') {
            // Create a Stripe Checkout session
            $checkoutURL = $this->stripeServices->createCheckoutSession($cartItems, $userId);
            if ($checkoutURL) {
                header("Location: " . $checkoutURL);
                exit();
            } else {
                echo "Stripe session creation failed.";
            }
        } else {
            echo "Invalid payment method selected.";
        }
    }




    public function checkout()
    {
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        $userId = $_SESSION['user_id'];

        // Retrieve cart items
        $cartItems = $this->cartModel->getCartItems($userId);
        $selectedItems = [];

        foreach ($cartItems as $item) {
            // Add only productName, sellingPrice, and quantity to the new array
            $selectedItems[] = [
                'productName' => $item->productName,
                'sellingPrice' => $item->sellingPrice,
                'quantity' => $item->quantity
            ];
        }

        $orderId = $this->orderModel->getLatestOrderIdByUserId($userId);

        // Check the current order status
        $currentStatus = $this->orderModel->getOrderStatusById($orderId);

        if ($currentStatus === 'pending') {
            $totalAmount = $this->orderModel->getTotalAmountByOrderId($orderId);
            $paymentMethod = $this->orderModel->getPaymentMethodByOrderId($orderId);

            // Update the order status to 'completed'
            $this->orderModel->updateOrderStatus($orderId, 'completed');

            // Add order items for showing in the admin dashboard
            foreach ($cartItems as $item) {
                $this->orderModel->addOrderItem($orderId, $item->id, $item->quantity);

                // Update stock for the purchased products
                // $this->productModel->reduceStock($item->id, $item->quantity);
            }

            // Clear the cart after successful order
            $this->orderModel->clearCart($userId);

            // Retrieve the user's email
            $userModel = $this->model('UserModel');
            $userEmail = $userModel->getEmailById($userId);
            $username = $userModel->getUserNameById($userId);

            // Send email notification
            $this->mailController->sendTransactionEmail($userEmail, $username, $orderId, $totalAmount, $paymentMethod, $selectedItems);

            $this->view('order/success');
        } else {
            // Handle cases where the order is not pending (optional)
            $this->view('order/refund_initiated');
        }
    }


    public function cancel()
    {
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        $userId = $_SESSION['user_id'];
        $orderId = $this->orderModel->getLatestOrderIdByUserId($userId);

        if ($orderId) {
            // Get the cart items for the canceled order
            $cartItems = $this->orderModel->getOrderItemsFromCart($orderId);

            // Restock the products by updating their quantities
            foreach ($cartItems as $item) {
                $productId = $item->productId;
                $quantityInCart = $item->quantity; // Quantity taken by the customer

                // Get the current stock for the product
                $product = $this->productModel->getProductById($productId);

                if ($product) {
                    // Increase stock by the quantity in the cart
                    $newStock = $product->stock + $quantityInCart; // Changed to 'stock'
                    // Update the stock in the database
                    $this->productModel->updateProductStock($productId, $newStock);
                }
            }

            // Update the order status to "canceled"
            $this->orderModel->updateOrderStatus($orderId, 'canceled');
        }

        // Redirect to the order cancellation confirmation page
        $this->view('order/cancel');
    }



    public function purchaseHistory()
    {
        // Ensure the user is logged in
        if (!Helper::isLoggedIn()) {
            Helper::redirect(URLROOT . "/UserController/login");
        }

        // Get the user ID from the session
        $userId = $_SESSION['user_id'];

        // Fetch purchased products for the user
        $purchasedProducts = $this->orderHistoryModel->getPurchasedProductsByUser($userId);

        // Check if products are found, if not, send a message
        if ($purchasedProducts) {
            $data = [
                'products' => $purchasedProducts,
            ];
        } else {
            $data = [
                'products' => [],
                'message' => 'You have no purchase history.',
            ];
        }

        // Pass data to the view
        $this->view('order_history/order_history', $data);
    }



    function getCoordinates($address)
    {
        // Geocoding API URL (Google Maps API example)
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=YOUR_GOOGLE_MAPS_API_KEY';

        // Make the request and parse the JSON response
        $response = file_get_contents($url);
        $data = json_decode($response);

        if ($data->status == 'OK') {
            // Get the latitude and longitude
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;

            return [$latitude, $longitude];
        } else {
            return null; // If the geocoding fails
        }
    }
}
