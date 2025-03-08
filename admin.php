<?php require 'helpers.php' ?>
<?php require 'database.php' ?>
<?php global $conn;

admin_authentication();

$sql_users = "SELECT COUNT(*) AS total_users FROM user_ac";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();

$sql_products = "SELECT COUNT(*) AS total_products FROM product";
$result_products = $conn->query($sql_products);
$row_products = $result_products->fetch_assoc();

$sql_orders = "SELECT COUNT(*) AS total_orders FROM cart";
$result_orders = $conn->query($sql_orders);
$row_orders = $result_orders->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="resources/favicon.ico" type="image/x-icon">
    <title>Craftcove Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>

<body class="bg-slate-100">
<!-- Sidebar -->
<div class="flex h-screen ">
    <div class="w-48 tracking-wide text-white h-full p-4 sidebar bg-gradient-to-r from-red-500 to-red-600 uppercase">
        <h1 class="text-2xl font-bold mb-4">Craftcove Admin <i class="fa-solid fa-screwdriver-wrench"></i></h1>
        <div class="pt-1 bg-white mb-5"></div>
        <nav class="mb-4">
            <ul>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_category.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-3"><i
                                class="fa-solid fa-layer-group"></i> <span>Category</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_tag.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-3 items-center"><i
                                class="fa-solid fa-tag"></i> <span>Tag</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_product.php"
                                                                    class="hover:text-gray-300 flex space-x-3 items-center"><i
                                class="fa-brands fa-draft2digital"></i>
                        <span>Product</span>
                    </a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_tag_product.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-3"><i
                                class="fa-solid fa-tags"></i> <span>Tag
                            Product</span></a></li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_user.php"
                                                                    class="hover:text-gray-300 flex space-x-3 items-center"><i
                                class="fa-solid fa-users-gear"></i> <span>User</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_cart_order.php"
                                                                    class="hover:text-gray-300 flex space-x-3 items-center"><i
                                class="fa-solid fa-cart-shopping"></i> <span>Cart</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_review.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-3"><i
                                class="fa-solid fa-comments"></i> <span>Review</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_coupon.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-3"><i
                                class="fa-solid fa-hand-holding-dollar"></i> <span>Coupon</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="admin_applied_coupon.php"
                                                                    class="hover:text-gray-300 flex items-center space-x-2"><i
                                class="fa-solid fa-ribbon"></i> <span>Applied Coupon</span></a>
                </li>
                <li class="text-md py-2 border-b border-red-700"><a href="index.php"
                                                                    class="hover:text-gray-300 space-x-3 flex items-center"><i
                                class="fa-solid fa-right-from-bracket"></i> <span>Exit</span></a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- Content -->
    <div class="flex-1 p-8 min-h-screen bg-gray-100">
        <h1 class="text-3xl font-semibold mb-8 text-center uppercase text-slate-700">Welcome to Craftcove Admin
            Panel</h1>
        <div class="pt-1 bg-slate-400"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center h-96">
            <div class="bg-white p-10 rounded-tl-full rounded-bl-full shadow-md card flex justify-center items-center flex-col">
                <h2 class="text-lg tracking-wide font-semibold mb-4 text-center uppercase">Total Users</h2>
                <div class="w-28 h-28 flex items-center justify-center rounded-full bg-yellow-500 hover:bg-orange-500 duration-300">
                    <p class="text-white text-3xl font-semibold"><?= $row_users['total_users'] ?></p>
                </div>
            </div>
            <div class="bg-white p-10 rounded-xl shadow-md card flex flex-col justify-center items-center">
                <h2 class="text-lg tracking-wide font-semibold mb-4 text-center uppercase">Total Products</h2>
                <div class="w-28 h-28 flex items-center justify-center rounded-full bg-green-500 hover:bg-orange-500 duration-300">
                    <p class="text-white text-3xl font-semibold"><?= $row_products['total_products'] ?></p>
                </div>
            </div>
            <div class="bg-white p-10 rounded-tr-full rounded-br-full shadow-md card flex flex-col justify-center items-center">
                <h2 class="text-lg tracking-wide font-semibold mb-4 text-center uppercase">Total Orders</h2>
                <div class="w-28 h-28 flex items-center justify-center rounded-full bg-sky-500 hover:bg-orange-500 duration-300">
                    <p class="text-white text-3xl font-semibold"><?= $row_orders['total_orders'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
