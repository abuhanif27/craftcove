<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="resources/favicon.ico" type="image/x-icon">
    <title>CraftCove | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body class="bg-slate-600">
<nav class="bg-slate-800">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a class="text-white text-md tracking-wide font-semibold uppercase" href="admin.php">Craft Cove Admin</a>
            <button class="text-white text-md focus:outline-none lg:hidden" aria-label="toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="hidden lg:flex lg:justify-end lg:items-center space-x-4 uppercase text-xs tracking-wider">
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_category.php">Category</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_tag.php">Tag</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_product.php">Product</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_tag_product.php">Tag Product</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_user.php">User</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_cart_order.php">Order-Cart</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_review.php">Review</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_coupon.php">Coupon</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8"
                       href="admin_applied_coupon.php">applied Coupon</a></li>
                <li><a class="text-white hover:text-gray-300 hover:underline hover:underline-offset-8" href="index.php">Exit</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="p-4 mx-auto text-gray-300">
