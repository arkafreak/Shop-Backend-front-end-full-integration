<?php

use \Stripe\Stripe;
use \Stripe\Checkout\Session;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey('sk_test_51QI0l6EJzWqJA7RbuAFhVBJVZxuUbpcDx1sOV1hghi9ZDqowgF9XAQOLZjSn4nxWxJTnrCfipAuqq9bOyIxulK5E00AJMCkR4f'); // Replace with actual Stripe Secret Key
    }

    public function createCheckoutSession($cartItems, $userId)
    {
        // Prepare line items from cart items
        $line_items = array_map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $item->productName,
                    ],
                    'unit_amount' => $item->sellingPrice * 100, // Stripe expects amount in cents
                ],
                'quantity' => $item->quantity,
            ];
        }, $cartItems);

        try {
            // Create a Stripe Checkout session
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => URLROOT . '/OrderController/checkout?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => URLROOT . '/checkoutController/cancel',
            ]);

            // die(var_dump($checkout_session));
            return $checkout_session->url;
        } catch (Exception $e) {
            error_log('Stripe error: ' . $e->getMessage());
            return false;
        }
    }
}