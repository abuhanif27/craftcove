<?php
require "config.php";
require "database.php";
global $conn;
$user_id = $_SESSION['user']['id'];

// Get all cart items for this user
$cart_items_sql = "SELECT c.id as cart_id, c.product_id, p.price as unit_price, c.total_price as price, 
                  p.name as product_name, p.description, c.quantity, c.coupon_used, p.stock 
                  FROM cart c
                  JOIN product p on c.product_id = p.id 
                  WHERE c.status = 'pending' and c.user_id = ? 
                  ORDER BY c.created_at DESC";

$stmt = $conn->prepare($cart_items_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$results = $stmt->get_result();
$num_items = mysqli_num_rows($results);

// Calculate cart total
$cart_total_sql = "SELECT SUM(total_price) as cart_total FROM cart 
                  WHERE user_id = ? AND status = 'pending'";
$stmt_total = $conn->prepare($cart_total_sql);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$cart_total = $total_row['cart_total'];

// Set session price for payment
if (isset($cart_total)) {
    $_SESSION['price'] = $cart_total;
    $amount = $cart_total * 100;
} else {
    $amount = 0;
}

// Create a description of all items in the cart
$description = "CraftCove Purchase: ";
$items = [];
while ($row = $results->fetch_assoc()) {
    $items[] = $row['product_name'] . " (x" . $row['quantity'] . ")";
}
$_SESSION['description'] = $description . implode(", ", $items);
?>

<form action="payment_submit.php" method="post">
    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="<?= STRIPE_PUBLISH_KEY ?>"
            data-amount="<?= $amount ?>"
            data-name="Securing your transaction..."
            data-description="CraftCove Ecommerce Website payment"
            data-image="payment_logo.png"
            data-email="<?= $_SESSION['user']['email'] ?>"
    ></script>
</form>
