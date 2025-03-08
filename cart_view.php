<?php
require 'helpers.php';
require 'database.php';
// No need to redeclare global $conn as it's already available from database.php

// Handle remove item request
if (isset($_POST['remove_item']) && isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    
    if ($cart_id <= 0) {
        $_SESSION['quantity_error'] = 'Invalid cart ID';
        header('Location: cart_view.php');
        exit;
    }
    
    try {
        // Get the cart item to restore product stock
        $cart_query = "SELECT product_id, quantity FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt_cart = $conn->prepare($cart_query);
        $stmt_cart->bind_param("ii", $cart_id, $user_id);
        $stmt_cart->execute();
        $cart_result = $stmt_cart->get_result();
        
        if ($cart_result->num_rows === 0) {
            throw new Exception("Cart item not found");
        }
        
        $cart_item = $cart_result->fetch_assoc();
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];
        
        // Restore product stock when removing from cart (real-time stock management)
        $update_stock_query = "UPDATE product SET stock = stock + ?, total_sold = total_sold - ? WHERE id = ?";
        $stmt_update_stock = $conn->prepare($update_stock_query);
        $stmt_update_stock->bind_param("iii", $quantity, $quantity, $product_id);
        $stmt_update_stock->execute();
        
        // Delete the cart item
        $delete_query = "DELETE FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("ii", $cart_id, $user_id);
        $stmt_delete->execute();
        
        if ($stmt_delete->affected_rows === 0) {
            throw new Exception("Failed to remove item from cart");
        }
        
        // Check if this was the last item in the cart
        $check_empty_query = "SELECT COUNT(*) as item_count FROM cart WHERE user_id = ? AND status = 'pending'";
        $stmt_check_empty = $conn->prepare($check_empty_query);
        $stmt_check_empty->bind_param("i", $user_id);
        $stmt_check_empty->execute();
        $check_empty_result = $stmt_check_empty->get_result();
        $check_empty_row = $check_empty_result->fetch_assoc();
        
        if ($check_empty_row['item_count'] == 0) {
            // Cart is now empty
            $_SESSION['cart_empty'] = true;
            $_SESSION['cart_msg'] = "Item removed from cart. Your cart is now empty.";
        } else {
            $_SESSION['cart_msg'] = "Item removed from cart successfully";
        }
    } catch (Exception $e) {
        $_SESSION['quantity_error'] = 'Error: ' . $e->getMessage();
    }
    
    header('Location: cart_view.php');
    exit;
}

// Handle quantity update request (now using form submission)
if (isset($_POST['update_quantity']) && isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    // Validate input parameters
    if ($cart_id <= 0 || $product_id <= 0) {
        $_SESSION['quantity_error'] = 'Invalid cart or product ID';
        header('Location: cart_view.php');
        exit;
    }
    
    try {
        // Validate quantity against stock
        $stock_query = "SELECT stock FROM product WHERE id = ?";
        $stmt_stock = $conn->prepare($stock_query);
        if (!$stmt_stock) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt_stock->bind_param("i", $product_id);
        if (!$stmt_stock->execute()) {
            throw new Exception("Execute failed: " . $stmt_stock->error);
        }
        
        $stock_result = $stmt_stock->get_result();
        if ($stock_result->num_rows === 0) {
            throw new Exception("Product not found");
        }
        
        $stock_row = $stock_result->fetch_assoc();
        $available_stock = $stock_row['stock'];
        
        if ($quantity <= 0) {
            // If quantity is 0 or less, remove the item from the cart
            // Get the cart item to restore product stock
            $cart_query = "SELECT quantity FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_cart = $conn->prepare($cart_query);
            $stmt_cart->bind_param("ii", $cart_id, $user_id);
            $stmt_cart->execute();
            $cart_result = $stmt_cart->get_result();
            
            if ($cart_result->num_rows === 0) {
                throw new Exception("Cart item not found");
            }
            
            $cart_item = $cart_result->fetch_assoc();
            $old_quantity = $cart_item['quantity'];
            
            // Restore product stock when removing from cart (real-time stock management)
            $update_stock_query = "UPDATE product SET stock = stock + ?, total_sold = total_sold - ? WHERE id = ?";
            $stmt_update_stock = $conn->prepare($update_stock_query);
            $stmt_update_stock->bind_param("iii", $old_quantity, $old_quantity, $product_id);
            $stmt_update_stock->execute();
            
            // Delete the cart item
            $delete_query = "DELETE FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param("ii", $cart_id, $user_id);
            $stmt_delete->execute();
            
            if ($stmt_delete->affected_rows === 0) {
                throw new Exception("Failed to remove item from cart");
            }
            
            // Check if this was the last item in the cart
            $check_empty_query = "SELECT COUNT(*) as item_count FROM cart WHERE user_id = ? AND status = 'pending'";
            $stmt_check_empty = $conn->prepare($check_empty_query);
            $stmt_check_empty->bind_param("i", $user_id);
            $stmt_check_empty->execute();
            $check_empty_result = $stmt_check_empty->get_result();
            $check_empty_row = $check_empty_result->fetch_assoc();
            
            if ($check_empty_row['item_count'] == 0) {
                // Cart is now empty
                $_SESSION['cart_empty'] = true;
                $_SESSION['cart_msg'] = "Item removed from cart. Your cart is now empty.";
            } else {
                $_SESSION['cart_msg'] = "Item removed from cart";
            }
            
            header('Location: cart_view.php');
            exit;
        } elseif ($quantity > $available_stock) {
            $_SESSION['quantity_error'] = 'Quantity exceeds available stock';
        } else {
            // Get the product price
            $price_query = "SELECT price FROM product WHERE id = ?";
            $stmt_price = $conn->prepare($price_query);
            if (!$stmt_price) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt_price->bind_param("i", $product_id);
            if (!$stmt_price->execute()) {
                throw new Exception("Execute failed: " . $stmt_price->error);
            }
            
            $price_result = $stmt_price->get_result();
            if ($price_result->num_rows === 0) {
                throw new Exception("Product price not found");
            }
            
            $price_row = $price_result->fetch_assoc();
            $unit_price = $price_row['price'];
            
            // Get current quantity to calculate stock difference
            $current_qty_query = "SELECT quantity FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_current_qty = $conn->prepare($current_qty_query);
            $stmt_current_qty->bind_param("ii", $cart_id, $user_id);
            $stmt_current_qty->execute();
            $current_qty_result = $stmt_current_qty->get_result();
            
            if ($current_qty_result->num_rows === 0) {
                throw new Exception("Cart item not found");
            }
            
            $current_qty_row = $current_qty_result->fetch_assoc();
            $current_quantity = $current_qty_row['quantity'];
            
            // Calculate quantity difference
            $quantity_diff = $quantity - $current_quantity;
            
            // Update stock based on quantity difference (real-time stock management)
            if ($quantity_diff != 0) {
                $update_stock_query = "UPDATE product SET stock = stock - ?, total_sold = total_sold + ? WHERE id = ?";
                $stmt_update_stock = $conn->prepare($update_stock_query);
                $stmt_update_stock->bind_param("iii", $quantity_diff, $quantity_diff, $product_id);
                $stmt_update_stock->execute();
            }
            
            // Calculate new total price
            $total_price = $quantity * $unit_price;
            
            // Check if coupon is applied
            $coupon_query = "SELECT coupon_used FROM cart WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_coupon = $conn->prepare($coupon_query);
            if (!$stmt_coupon) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt_coupon->bind_param("ii", $cart_id, $user_id);
            if (!$stmt_coupon->execute()) {
                throw new Exception("Execute failed: " . $stmt_coupon->error);
            }
            
            $coupon_result = $stmt_coupon->get_result();
            if ($coupon_result->num_rows === 0) {
                throw new Exception("Cart not found");
            }
            
            $coupon_row = $coupon_result->fetch_assoc();
            
            // If coupon is applied, get the discount
            if ($coupon_row && $coupon_row['coupon_used'] == 1) {
                // Get the latest applied coupon for this user
                $discount_query = "SELECT cc.discount 
                                  FROM applied_coupon ac 
                                  JOIN coupon_code cc ON ac.coupon_id = cc.id 
                                  WHERE ac.user_id = ? AND ac.applied = 1 
                                  ORDER BY ac.id DESC LIMIT 1";
                $stmt_discount = $conn->prepare($discount_query);
                if (!$stmt_discount) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $stmt_discount->bind_param("i", $user_id);
                if (!$stmt_discount->execute()) {
                    throw new Exception("Execute failed: " . $stmt_discount->error);
                }
                
                $discount_result = $stmt_discount->get_result();
                
                if ($discount_result->num_rows > 0) {
                    $discount_row = $discount_result->fetch_assoc();
                    $discount = $discount_row['discount'];
                    $total_price = $total_price - ($total_price * $discount / 100);
                }
            }
            
            // Update cart
            $update_query = "UPDATE cart SET quantity = ?, total_price = ? WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_update = $conn->prepare($update_query);
            if (!$stmt_update) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt_update->bind_param("idii", $quantity, $total_price, $cart_id, $user_id);
            if (!$stmt_update->execute()) {
                throw new Exception("Execute failed: " . $stmt_update->error);
            }
            
            if ($stmt_update->affected_rows === 0) {
                throw new Exception("No rows updated. Cart may not exist or status is not 'pending'.");
            }
            
            // Update session price
            $_SESSION['price'] = $total_price;
            $_SESSION['quantity_success'] = 'Quantity updated successfully';
        }
    } catch (Exception $e) {
        $_SESSION['quantity_error'] = 'Error: ' . $e->getMessage();
    }
    
    // Redirect back to cart page
    header('Location: cart_view.php');
    exit;
}

// Process coupon form submission before any output
if (isset($_POST['coupon_btn']) && isset($_SESSION['user']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user']['id'];
    
    // Get all cart items for this user
    $cart_query = "SELECT SUM(total_price) as cart_total, COUNT(*) as item_count FROM cart 
                  WHERE user_id = ? AND status = 'pending'";
    $stmt_cart = $conn->prepare($cart_query);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $cart_result = $stmt_cart->get_result();
    
    if ($cart_result->num_rows > 0) {
        $cart_summary = $cart_result->fetch_assoc();
        
        if ($cart_summary['item_count'] == 0) {
            $_SESSION['coupon_error'] = "Your cart is empty";
            header('Location: cart_view.php');
            exit;
        }
        
        // Check if any cart item already has a coupon applied
        $coupon_check_query = "SELECT COUNT(*) as coupon_count FROM cart 
                              WHERE user_id = ? AND status = 'pending' AND coupon_used = 1";
        $stmt_coupon_check = $conn->prepare($coupon_check_query);
        $stmt_coupon_check->bind_param("i", $user_id);
        $stmt_coupon_check->execute();
        $coupon_check_result = $stmt_coupon_check->get_result();
        $coupon_check = $coupon_check_result->fetch_assoc();
        
        if ($coupon_check['coupon_count'] > 0) {
            $_SESSION['already_coupon_applied'] = "You have already redeemed a coupon, so you cannot apply multiple coupon codes in your cart.";
            header('Location: cart_view.php');
            exit;
        }
        
        $coupon_code = isset($_POST['coupon_code']) ? trim($_POST['coupon_code']) : '';
        
        if (empty($coupon_code)) {
            $_SESSION['coupon_error'] = "Please enter a coupon code";
            header('Location: cart_view.php');
            exit;
        }
        
        // First check if the coupon exists in the coupon_code table
        $coupon_check_sql = "SELECT id, name, discount FROM coupon_code WHERE name = ?";
        $stmt_coupon_check = $conn->prepare($coupon_check_sql);
        $stmt_coupon_check->bind_param("s", $coupon_code);
        $stmt_coupon_check->execute();
        $coupon_check_result = $stmt_coupon_check->get_result();
        
        if ($coupon_check_result->num_rows === 0) {
            $_SESSION['coupon_error'] = "Invalid coupon code: Coupon does not exist";
            header('Location: cart_view.php');
            exit;
        }
        
        $coupon_data = $coupon_check_result->fetch_assoc();
        $coupon_id = $coupon_data['id'];
        $discount = $coupon_data['discount'];
        
        // Check if this coupon is already assigned to the user in applied_coupon table
        $applied_check_sql = "SELECT id, applied FROM applied_coupon WHERE user_id = ? AND coupon_id = ?";
        $stmt_applied_check = $conn->prepare($applied_check_sql);
        $stmt_applied_check->bind_param("ii", $user_id, $coupon_id);
        $stmt_applied_check->execute();
        $applied_check_result = $stmt_applied_check->get_result();
        
        if ($applied_check_result->num_rows === 0) {
            // Coupon not assigned to user yet, create a new entry
            $insert_applied_sql = "INSERT INTO applied_coupon (user_id, coupon_id, applied) VALUES (?, ?, 0)";
            $stmt_insert_applied = $conn->prepare($insert_applied_sql);
            $stmt_insert_applied->bind_param("ii", $user_id, $coupon_id);
            $stmt_insert_applied->execute();
            
            if ($stmt_insert_applied->affected_rows === 0) {
                $_SESSION['coupon_error'] = "Error assigning coupon to user";
                header('Location: cart_view.php');
                exit;
            }
        } else {
            $applied_data = $applied_check_result->fetch_assoc();
            if ($applied_data['applied'] == 1) {
                $_SESSION['coupon_error'] = "This coupon has already been used";
                header('Location: cart_view.php');
                exit;
            }
        }
        
        // Get all cart items
        $cart_items_query = "SELECT id, product_id, quantity, total_price FROM cart 
                            WHERE user_id = ? AND status = 'pending'";
        $stmt_cart_items = $conn->prepare($cart_items_query);
        $stmt_cart_items->bind_param("i", $user_id);
        $stmt_cart_items->execute();
        $cart_items_result = $stmt_cart_items->get_result();
        
        $total_discount = 0;
        $update_success = true;
        
        // Apply discount to each cart item
        while ($cart_item = $cart_items_result->fetch_assoc()) {
            $item_price = $cart_item['total_price'];
            $discounted_price = $item_price - ($item_price * $discount / 100);
            $item_discount = $item_price - $discounted_price;
            $total_discount += $item_discount;
            
            // Update cart item with discounted price and mark coupon as used
            $update_item_sql = "UPDATE cart SET total_price = ?, coupon_used = 1 
                               WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt_update_item = $conn->prepare($update_item_sql);
            $stmt_update_item->bind_param("dii", $discounted_price, $cart_item['id'], $user_id);
            $stmt_update_item->execute();
            
            if ($stmt_update_item->affected_rows === 0) {
                $update_success = false;
                break;
            }
        }
        
        if (!$update_success) {
            $_SESSION['coupon_error'] = "Error updating cart with coupon discount";
            header('Location: cart_view.php');
            exit;
        }
        
        // Mark the coupon as applied
        $update_coupon_sql = "UPDATE applied_coupon SET applied = 1 WHERE user_id = ? AND coupon_id = ?";
        $stmt_update_coupon = $conn->prepare($update_coupon_sql);
        $stmt_update_coupon->bind_param("ii", $user_id, $coupon_id);
        $stmt_update_coupon->execute();
        
        if ($stmt_update_coupon->affected_rows === 0) {
            // This is not a critical error, so we'll just log it
            error_log("Failed to mark coupon as applied for user $user_id, coupon $coupon_id");
        }
        
        // Calculate new cart total after discount
        $new_total = $cart_summary['cart_total'] - $total_discount;
        
        // Update session price
        $_SESSION['price'] = $new_total;
        $_SESSION['coupon_code'] = "'" . $coupon_code . "'" . " Coupon Applied successfully! You got " . $discount . "% discount, saving $" . number_format($total_discount, 2) . "!";
        
        header('Location: cart_view.php');
        exit;
    } else {
        $_SESSION['coupon_error'] = "No active cart found";
        header('Location: cart_view.php');
        exit;
    }
}

// Now it's safe to include the header (which outputs HTML)
loadPartial('header');

if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    
    // Get all cart items for this user
    $cart_items_sql = "SELECT c.id as cart_id, c.product_id, p.price as unit_price, c.total_price as price, 
                      p.name as product_name, p.description, c.quantity, c.coupon_used, p.stock, p.img_url 
                      FROM cart c
                      JOIN product p on c.product_id = p.id 
                      WHERE c.status = 'pending' and c.user_id = ? 
                      ORDER BY c.created_at DESC";
    
    $stmt = $conn->prepare($cart_items_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $results = $stmt->get_result();
    $num_items = mysqli_num_rows($results);

    if ($num_items > 0) {
        // Calculate cart total
        $cart_total_sql = "SELECT SUM(total_price) as cart_total FROM cart 
                          WHERE user_id = ? AND status = 'pending'";
        $stmt_total = $conn->prepare($cart_total_sql);
        $stmt_total->bind_param("i", $user_id);
        $stmt_total->execute();
        $total_result = $stmt_total->get_result();
        $total_row = $total_result->fetch_assoc();
        $cart_total = $total_row['cart_total'];
        
        $_SESSION['price'] = (float)$cart_total;
        ?>
        <?php if (!empty($_SESSION['cart_msg'])) : ?>
            <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert">
                <span class="block sm:inline"><?= $_SESSION['cart_msg'] ?></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 hover:text-green-900" aria-label="Close" onclick="closeSuccessAlert()">
                    <span aria-hidden="true" class="text-xl">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['cart_msg']) ?>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['quantity_success'])) : ?>
            <div id="quantity-success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert">
                <span class="block sm:inline"><?= $_SESSION['quantity_success'] ?></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 hover:text-green-900" aria-label="Close" onclick="closeQuantitySuccessAlert()">
                    <span aria-hidden="true" class="text-xl">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['quantity_success']) ?>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['quantity_error'])) : ?>
            <div id="quantity-error-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert">
                <span class="block sm:inline"><?= $_SESSION['quantity_error'] ?></span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 hover:text-red-900" aria-label="Close" onclick="closeQuantityErrorAlert()">
                    <span aria-hidden="true" class="text-xl">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['quantity_error']) ?>
        <?php endif; ?>
        
        <div class="container mx-auto my-8">
            <h2 class="text-2xl font-bold mb-4">Shopping Cart</h2>
            <div class="bg-white p-4 rounded-lg shadow-md mb-8">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                
                <?php while ($item = $results->fetch_assoc()) : ?>
                <div class="flex justify-between items-center border-b border-gray-300 py-4">
                    <div class="flex items-center">
                        <?php if ($item['img_url']) : ?>
                            <img src="<?= $item['img_url'] ?>" alt="<?= $item['product_name'] ?>" class="w-16 h-16 object-cover rounded mr-4">
                        <?php else : ?>
                            <div class="w-16 h-16 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                <span class="text-gray-500">No image</span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h4 class="text-gray-800 font-semibold"><?= $item['product_name'] ?></h4>
                            <p class="text-gray-600 text-sm"><?= substr($item['description'], 0, 100) . (strlen($item['description']) > 100 ? '...' : '') ?></p>
                        </div>
                    </div>
                    <div class="text-gray-800 flex items-center">
                        <div class="mr-4">
                            <span>Unit Price: $<span class="unit-price"><?= number_format($item['unit_price'], 2) ?></span></span>
                            <div class="text-sm text-gray-500">
                                Subtotal: $<span class="item-price"><?= number_format($item['price'], 2) ?></span>
                            </div>
                        </div>
                        <div class="flex items-center border border-gray-300 rounded-md">
                            <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 quantity-btn" data-action="decrease" data-cart-id="<?= $item['cart_id'] ?>">-</button>
                            <input type="number" 
                                   class="w-16 text-center border-x border-gray-300 py-1 focus:outline-none quantity-input" 
                                   value="<?= $item['quantity'] ?>" 
                                   min="1" 
                                   max="<?= $item['stock'] ?>"
                                   data-cart-id="<?= $item['cart_id'] ?>"
                                   data-product-id="<?= $item['product_id'] ?>"
                                   data-unit-price="<?= $item['unit_price'] ?>"
                                   data-stock="<?= $item['stock'] ?>">
                            <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 quantity-btn" data-action="increase" data-cart-id="<?= $item['cart_id'] ?>">+</button>
                        </div>
                        <button type="button" class="ml-4 text-red-500 hover:text-red-700 hover:scale-110 transition-all duration-200 remove-item-btn" data-cart-id="<?= $item['cart_id'] ?>" title="Remove item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <div id="quantity-error" class="text-red-500 text-sm mt-2 hidden"></div>

                <div class="flex justify-between items-center pt-4 mt-4">
                    <h4 class="text-lg font-semibold">Total:</h4>
                    <div class="text-lg font-semibold">$<span id="total-price"><?= number_format($cart_total, 2) ?></span></div>
                </div>
            </div>
            <div class="bg-gray-100 p-6 rounded-lg shadow-lg mb-8">
                <?php if (!empty($_SESSION['coupon_code'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['coupon_code'] ?></span>
                    </div>
                    <?php unset($_SESSION['coupon_code']) ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['already_coupon_applied'])): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['already_coupon_applied'] ?></span>
                    </div>
                    <?php unset($_SESSION['already_coupon_applied']) ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['coupon_error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?= $_SESSION['coupon_error'] ?></span>
                    </div>
                    <?php unset($_SESSION['coupon_error']) ?>
                <?php endif; ?>

                <form method="POST" action="cart_view.php">
                    <div class="mb-4 inline">
                        <input type="text" id="coupon" name="coupon_code"
                               class="inline w-[75%] px-4 py-2 border border-orange-300 focus:outline-none rounded-lg focus:ring focus:ring-orange-300 focus:border-orange-400"
                               placeholder="Enter your coupon code">
                    </div>

                    <div class="text-center inline-block w-[12.5%] ml-[12%]">
                        <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 focus:outline-none focus:ring focus:ring-orange-500 focus:border-orange-500 w-full"
                                name="coupon_btn">
                            Apply Coupon
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex justify-end mr-10">
                <?= loadPartial('payment') ?>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="container mx-auto my-8">
            <?php if (isset($_SESSION['cart_empty']) && $_SESSION['cart_empty']): ?>
            <div id="empty-cart-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert">
                <span class="block sm:inline">Your shopping cart is empty.</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3 hover:text-red-900" aria-label="Close" onclick="closeAlert()">
                    <span aria-hidden="true" class="text-xl">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['cart_empty']); ?>
            <?php endif; ?>
            
            <div class="bg-gray-100 min-h-screen flex justify-center items-center">
                <div class="max-w-md p-8 bg-white rounded-lg shadow-md text-center">
                    <h2 class="text-red-500 text-3xl font-semibold mb-6">No Items in Cart</h2>
                    <p class="text-gray-700 mb-6">Your shopping cart is currently empty. Add some items to get started!</p>
                    <a href="index.php"
                       class="bg-orange-500 text-white py-2 px-6 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50 transition-colors duration-300">
                        Start Shopping
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}
else {
    ?>
    <div class="bg-gray-100 min-h-screen flex justify-center items-center">
        <div class="max-w-md p-8 bg-white rounded-lg shadow-md text-center">
            <h2 class="text-red-500 text-3xl font-semibold mb-6">No Orders Yet</h2>
            <p class="text-gray-700 mb-6">You haven't placed any orders yet.</p>
            <a href="index.php"
               class="bg-red-500 text-white py-2 px-6 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-300">
                Start Shopping
            </a>
        </div>
    </div>
    <?php
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        const removeItemBtns = document.querySelectorAll('.remove-item-btn');
        const quantityError = document.getElementById('quantity-error');
        const totalPriceElement = document.getElementById('total-price');
        const emptyCartAlert = document.getElementById('empty-cart-alert');
        const successAlert = document.getElementById('success-alert');
        const quantitySuccessAlert = document.getElementById('quantity-success-alert');
        const quantityErrorAlert = document.getElementById('quantity-error-alert');
        
        // Auto-hide alerts after 5 seconds
        const alerts = [
            emptyCartAlert,
            successAlert,
            quantitySuccessAlert,
            quantityErrorAlert
        ];
        
        alerts.forEach(alert => {
            if (alert) {
                setTimeout(function() {
                    fadeOut(alert, 500);
                }, 5000);
            }
        });
        
        function fadeOut(element, duration) {
            let opacity = 1;
            const interval = 10;
            const delta = interval / duration;
            
            const fadeOutInterval = setInterval(function() {
                opacity -= delta;
                element.style.opacity = opacity;
                
                if (opacity <= 0) {
                    clearInterval(fadeOutInterval);
                    element.style.display = 'none';
                }
            }, interval);
        }
        
        function closeAlert() {
            const alert = document.getElementById('empty-cart-alert');
            if (alert) {
                fadeOut(alert, 300);
            }
        }
        
        function closeSuccessAlert() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                fadeOut(alert, 300);
            }
        }
        
        function closeQuantitySuccessAlert() {
            const alert = document.getElementById('quantity-success-alert');
            if (alert) {
                fadeOut(alert, 300);
            }
        }
        
        function closeQuantityErrorAlert() {
            const alert = document.getElementById('quantity-error-alert');
            if (alert) {
                fadeOut(alert, 300);
            }
        }
        
        // Make close functions available globally
        window.closeAlert = closeAlert;
        window.closeSuccessAlert = closeSuccessAlert;
        window.closeQuantitySuccessAlert = closeQuantitySuccessAlert;
        window.closeQuantityErrorAlert = closeQuantityErrorAlert;
        
        if (quantityInputs.length > 0) {
            // Create a hidden form for quantity updates
            const updateForm = document.createElement('form');
            updateForm.method = 'POST';
            updateForm.action = 'cart_view.php';
            updateForm.style.display = 'none';
            
            const updateQuantityInput = document.createElement('input');
            updateQuantityInput.type = 'hidden';
            updateQuantityInput.name = 'update_quantity';
            updateQuantityInput.value = '1';
            
            const cartIdInput = document.createElement('input');
            cartIdInput.type = 'hidden';
            cartIdInput.name = 'cart_id';
            
            const productIdInput = document.createElement('input');
            productIdInput.type = 'hidden';
            productIdInput.name = 'product_id';
            
            const quantityValueInput = document.createElement('input');
            quantityValueInput.type = 'hidden';
            quantityValueInput.name = 'quantity';
            
            updateForm.appendChild(updateQuantityInput);
            updateForm.appendChild(cartIdInput);
            updateForm.appendChild(productIdInput);
            updateForm.appendChild(quantityValueInput);
            
            document.body.appendChild(updateForm);
            
            // Create a hidden form for removing items
            const removeForm = document.createElement('form');
            removeForm.method = 'POST';
            removeForm.action = 'cart_view.php';
            removeForm.style.display = 'none';
            
            const removeItemInput = document.createElement('input');
            removeItemInput.type = 'hidden';
            removeItemInput.name = 'remove_item';
            removeItemInput.value = '1';
            
            const removeCartIdInput = document.createElement('input');
            removeCartIdInput.type = 'hidden';
            removeCartIdInput.name = 'cart_id';
            
            removeForm.appendChild(removeItemInput);
            removeForm.appendChild(removeCartIdInput);
            
            document.body.appendChild(removeForm);
            
            // Handle quantity buttons
            quantityBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const cartId = this.dataset.cartId;
                    const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                    const maxStock = parseInt(input.dataset.stock);
                    let currentQty = parseInt(input.value);
                    
                    if (action === 'increase' && currentQty < maxStock) {
                        currentQty++;
                    } else if (action === 'decrease') {
                        // Allow decreasing to 0, which will trigger item removal on the server
                        currentQty--;
                    }
                    
                    // Update the form's values
                    cartIdInput.value = cartId;
                    productIdInput.value = input.dataset.productId;
                    quantityValueInput.value = currentQty;
                    
                    // Submit the form to reload the page
                    updateForm.submit();
                });
            });
            
            // Handle direct input changes
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const cartId = this.dataset.cartId;
                    const maxStock = parseInt(this.dataset.stock);
                    let newQty = parseInt(this.value);
                    
                    if (isNaN(newQty) || newQty < 1) {
                        newQty = 1;
                        this.value = 1;
                        showError('Quantity must be at least 1');
                    } else if (newQty > maxStock) {
                        newQty = maxStock;
                        this.value = maxStock;
                        showError('Quantity cannot exceed available stock of ' + maxStock);
                    } else {
                        hideError();
                    }
                    
                    // Update the form's values
                    cartIdInput.value = cartId;
                    productIdInput.value = this.dataset.productId;
                    quantityValueInput.value = newQty;
                    
                    // Submit the form to reload the page
                    updateForm.submit();
                });
            });
            
            // Handle remove item buttons
            removeItemBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Get the cart ID
                    const cartId = this.dataset.cartId;
                    
                    // Set the cart ID in the remove form
                    removeCartIdInput.value = cartId;
                    
                    // Submit the form directly without confirmation
                    removeForm.submit();
                });
            });
            
            function showError(message) {
                if (quantityError) {
                    quantityError.textContent = message;
                    quantityError.classList.remove('hidden');
                }
            }
            
            function hideError() {
                if (quantityError) {
                    quantityError.classList.add('hidden');
                }
            }
        }
        
        const closeAlertBtn = document.querySelector('#closeAlertBtn');
        const alert = document.querySelector('#alert');
        if (closeAlertBtn && alert) {
            closeAlertBtn.addEventListener('click', () => {
                alert.style.display = 'none';
            });
        }
    });
</script>
<?php loadPartial('footer');
?>