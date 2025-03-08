<?php
require "helpers.php";
require "config.php";
require 'database.php';
global $conn;
$user_id = $_SESSION['user']['id'];

// Stock is already updated in real-time when cart quantities change
// No need to update stock here

// Update all pending cart items to complete status
$sql = "UPDATE cart SET status='complete' WHERE user_id = ? AND status='pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

try {
    \Stripe\Stripe::setVerifySslCerts(false);

    $token = $_POST['stripeToken'];

    if (!isset($_SESSION['price']) || !isset($_SESSION['description'])) {
        throw new Exception('Session variables not set.');
    }

    \Stripe\Charge::create(array(
        "amount" => $_SESSION['price']*100,
        "currency" => "usd",
        "description" => $_SESSION['description'],
        "source" => $token,
    ));

    unset($_SESSION['price']);
    unset($_SESSION['description']);


} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'Stripe API Error: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<?php loadPartial("header"); ?>
<div class="min-h-96 flex flex-col justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full mx-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 mx-auto" viewBox="0 0 20 20"
             fill="currentColor">
            <path fill-rule="evenodd"
                  d="M10 19a9 9 0 100-18 9 9 0 000 18zm-1.414-3.586l6-6-1.414-1.414-5.293 5.293-2.293-2.293L5 12.293 8.293 15.5l.707-.707z"
                  clip-rule="evenodd"/>
        </svg>
        <h2 class="text-3xl font-semibold text-center text-gray-800 mt-4">Payment Successful!</h2>
        <p class="text-lg text-center text-gray-600 mt-2">Your payment has been processed successfully.</p>
        <div class="flex justify-center mt-6">
            <a href="index.php" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-md mr-4 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition duration-300 ease-in-out transform hover:scale-105">Continue Shopping</a>
        </div>
    </div>
</div>

<?php loadPartial("footer"); ?>
