<?php
require 'helpers.php';
require 'database.php';
global $conn;

if (!is_user_logged_in()) {
    echo "<script>window.location.href='login_view.php';</script>";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout_btn'])) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['logout_msg'] = "You are successfully logged out!";
    echo "<script>window.location.href='login_view.php';</script>";
    exit();
}


$user_id = $_SESSION['user']['id'];

// Always fetch the latest user data from the database
$profile_sql = "SELECT * FROM user_ac WHERE id = '$user_id'";
$profile_result = mysqli_query($conn, $profile_sql);
$row = mysqli_fetch_assoc($profile_result);

// Update session with latest user data to keep it in sync
$_SESSION['user'] = $row;

$sql = "SELECT id, total_price, created_at, status FROM cart WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

$cancel_order_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_btn'])) {
    $order_id = $_POST['order_id'];
    $sql_delete = "DELETE FROM cart WHERE id = $order_id AND user_id = $user_id AND status = 'pending'";
    if ($conn->query($sql_delete) === TRUE) {
        unset($_SESSION['price']);
        $cancel_order_message = "<div id='alert' class='flex items-center p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50' role='alert'>
                <svg class='flex-shrink-0 w-4 h-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor'
                     viewBox='0 0 20 20'>
                    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
                </svg>
                <span class='sr-only'>Info</span>
                <div class='ms-3 text-sm font-medium'>
                    Order Cancel Successfully!
                </div>
                <button type='button'
                        class='ms-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex items-center justify-center h-8 w-8 '
                       aria-label='Close' id='closeAlertBtn'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                              d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>";
    } else {
        $cancel_order_message = "<div id='alert' class='flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50' role='alert'>
                <svg class='flex-shrink-0 w-4 h-4' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor'
                     viewBox='0 0 20 20'>
                    <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z'/>
                </svg>
                <span class='sr-only'>Info</span>
                <div class='ms-3 text-sm font-medium'>
                    Error cancelling order!
                </div>
                <button type='button'
                        class='ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 '
                        aria-label='Close' id='closeAlertBtn'>
                    <span class='sr-only'>Close</span>
                    <svg class='w-3 h-3' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 14 14'>
                        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                              d='m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6'/>
                    </svg>
                </button>
            </div>";
    }
}

loadPartial('header');
?>

<?php if (!empty($cancel_order_message)) : ?>
    <?php echo $cancel_order_message ?>
<?php endif; ?>

<?php if (!empty($_SESSION['login_msg'])) : ?>
    <?php echo success_message($_SESSION['login_msg']) ?>
    <?php unset($_SESSION['login_msg']) ?>
<?php endif; ?>
<?php if (!empty($_SESSION['register_msg'])) : ?>
    <?php echo success_message($_SESSION['register_msg']) ?>
    <?php unset($_SESSION['register_msg']) ?>
<?php endif; ?>

<div class="bg-gray-100 py-20">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-12 text-slate-700 underline underline-offset-8 uppercase tracking-wide">
            My Account</h1>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex  justify-around items-center ">

                    <div class="flex items-center justify-center py-6">

                        <div class="w-64 h-64 rounded-full overflow-hidden bg-gray-300 flex items-center justify-center">
                            <?php if ($row['profile_img']): ?>
                                <img src="<?= $row['profile_img'] ?>?v=<?= time() ?>" alt="user profile picture">
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-500"
                                     viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M3 10a5 5 0 1110 0 5 5 0 01-10 0zm7-5a1 1 0 00-2 0v1a1 1 0 102 0V5zM5 11a5.002 5.002 0 005.408 4.996c.274.022.48-.254.332-.518A6.989 6.989 0 015 11.99v-.994A5.002 5.002 0 005 11zm9-1a1 1 0 00-2 0v1a1 1 0 102 0V10zm-2-1a3 3 0 11-6 0 3 3 0 016 0z"
                                          clip-rule="evenodd"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="">
                        <div class="shadow">
                            <h2 class="text-2xl font-bold mb-4 uppercase text-slate-700 ">Personal
                                Information</h2>
                            <ul class="list-none m-0 p-0">
                                <li class="flex items-center mb-4">
                                    <span class="font-bold w-32">Name:</span>
                                    <span class="text-gray-600"><?= $row['name'] ?></span>
                                </li>
                                <li class="flex items-center mb-4">
                                    <span class="font-bold w-32">Email:</span>
                                    <span class="text-gray-600"><?= $row['email'] ?></span>
                                </li>
                                <li class="flex items-center mb-4">
                                    <span class="font-bold w-32">Phone:</span>
                                    <span class="text-gray-600"><?= $row['phone'] ?></span>
                                </li>
                                <li class="flex items-center">
                                    <span class="font-bold w-32">Address:</span>
                                    <span class="text-gray-600"><?= $row['address'] ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="pt-1 bg-slate-200 mt-4"></div>
                <div class="mt-8">
                    <form action="profile.php" method='POST'>
                        <div class="flex justify-center">
                            <a href="user_profile_edit.php?id=<?= $row['id'] ?>"
                               class="text-blue-500 hover:text-blue-600 mr-4"><i class="fa-solid fa-pencil"></i> Edit
                                Information</a>
                            <span class="text-gray-400">|</span>
                            <button name="logout_btn" class="text-red-500 hover:text-red-600 ml-4">Logout <i
                                        class="fa-solid fa-power-off"></i></button>
                        </div>
                    </form>
                </div>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-5 text-center text-slate-600 underline underline-offset-8 uppercase">
                            Order History</h2>
                        <div class="flex justify-around">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <div class="bg-gray-200 p-4 rounded-lg w-[45%] flex flex-col justify-center space-y-2">
                                    <p class="text-gray-600 text-sm"><span
                                                class="font-bold uppercase">Order ID #<?= $row['id'] ?></span>
                                        -
                                        Date/Time: <?= getTimeElapsedString($row['created_at']) ?></p>
                                    <p class="text-gray-600 text-sm"><span class="font-bold uppercase">Total:</span>
                                        $<?= $row['total_price'] ?></p>
                                    <p class="text-gray-600 text-sm"><span class="font-bold uppercase">Status:</span>
                                        <?= $row['status'] ?></p>
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <form action="profile.php" method="POST">
                                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                            <button type="submit"
                                                    class="text-red-500 border px-3 py-1 border-red-500 rounded-full hover:bg-red-500 hover:text-white"
                                                    name="cancel_btn">Cancel Order
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                            <?php
                            $total_order_sql = "SELECT COUNT(*) AS total_order_count, SUM(total_price) AS total_order_price FROM cart WHERE user_id = '$user_id'";
                            $total_order_result = mysqli_query($conn, $total_order_sql);
                            $total_order_row = mysqli_fetch_assoc($total_order_result);
                            ?>
                            <div class="bg-gray-200 p-4 rounded-lg w-[45%] flex flex-col justify-center space-y-2">
                                <p class="text-gray-600 text-sm"><span class="font-bold uppercase">I Total Spent:</span>
                                    $<?= $total_order_row['total_order_price'] ?></p>
                                <p class="text-gray-600 text-sm"><span
                                            class="font-bold uppercase">I Ordered:</span>
                                    <?= $total_order_row['total_order_count'] == 1 ? '1st Time' : $total_order_row['total_order_count'] . ' Times' ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<script>
    const closeAlertBtn = document.querySelector('#closeAlertBtn');
    const alert = document.querySelector('#alert');
    if (closeAlertBtn && alert) {
        closeAlertBtn.addEventListener('click', () => {
            alert.style.display = 'none';
            window.location.href = "profile.php";
        });
    }
</script>
<?php
loadPartial('footer');
?>
