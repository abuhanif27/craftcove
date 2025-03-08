<?php
global $conn;
if (isset($_POST['coupon_btn']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_code = $_POST['coupon_code'];

    $coupon_sql = "SELECT cc.id as id, name, discount, applied FROM applied_coupon ac
                JOIN coupon_code cc ON ac.coupon_id = cc.id
                WHERE applied = 0 AND user_id = ? AND name=?";

    $stmt_coupon = $conn->prepare($coupon_sql);
    $stmt_coupon->bind_param("is", $user_id, $coupon_code);
    $stmt_coupon->execute();
    $results_coupon = $stmt_coupon->get_result();

    if ($results_coupon->num_rows > 0) {
        $row_coupon = $results_coupon->fetch_assoc();
        $discount = $row_coupon['discount'];
        $new_price = $_SESSION['price'] - ($_SESSION['price'] * $discount / 100); // Corrected calculation
        $_SESSION['price'] = $new_price;

        $sql_update_coupon = "UPDATE applied_coupon SET applied = 1 WHERE user_id = ? AND coupon_id=? AND applied = 0";
        $stmt_update_coupon = $conn->prepare($sql_update_coupon);
        $stmt_update_coupon->bind_param("ii", $user_id, $row_coupon['id']);
        $stmt_update_coupon->execute();
        if ($stmt_update_coupon) {
            $_SESSION['coupon_code'] = $coupon_code . " Applied successfully you got " . $discount . " % discount!";
        }
    } else {
        $_SESSION['coupon_error'] = "Invalid coupon code!";
    }
}
?>
