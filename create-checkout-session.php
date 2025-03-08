<?php
require_once 'stripe-php/init.php';
$stripe_secret_key = 'sk_test_51OxYrSRogH7j6YJA6HUWGRaqkSbAvFLmOLg95WHfWJIjfyFh1HcpDKyhqvIk1HdQA1sqbyMbLvZAjEY5QxW4NlPa00QZPAd1hK'; // Replace with your actual secret key
\Stripe\Stripe::setApiKey($stripe_secret_key);

$product_name = 'Product Name';
$product_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
$product_price = 9999; // Product price in cents (e.g., $99.99 is 9999)

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => $product_name,
                'description' => $product_description,
            ],
            'unit_amount' => $product_price,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'https://example.com/success',
    'cancel_url' => 'https://example.com/cancel',
]);

header('Content-Type: application/json');
echo json_encode(['id' => $session->id]);
