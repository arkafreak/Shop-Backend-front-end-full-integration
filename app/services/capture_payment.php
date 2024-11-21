<?php
// PayPal configuration
define('PAYPAL_CLIENT_ID', 'Acwul7cJu4m2PiwDoyqnxBQ6Gz5l1Kv13jbfk6m0GKWT13fgMS9yvXjTjs-ds82ppHT5DQ9dYY0ObVHj');
define('PAYPAL_SECRET', 'YOUR_SECRET_KEY'); // Replace with your secret key
define('PAYPAL_API_URL', 'https://api.sandbox.paypal.com'); // Use sandbox for testing

// Capture payment function
function capturePayment($orderID) {
    $url = PAYPAL_API_URL . "/v2/checkout/orders/$orderID/capture";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Basic " . base64_encode(PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET)
    ]);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        throw new Exception(curl_error($curl));
    }
    curl_close($curl);

    return json_decode($response, true);
}

// Retrieve JSON POST data
$data = json_decode(file_get_contents("php://input"));

// Ensure that orderID is provided
if (!isset($data->orderID)) {
    http_response_code(400);
    echo json_encode(['error' => 'Order ID missing']);
    exit;
}

try {
    // Capture the payment
    $result = capturePayment($data->orderID);

    // Process result (e.g., save to database, update order status)
    echo json_encode(['status' => 'success', 'data' => $result]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
