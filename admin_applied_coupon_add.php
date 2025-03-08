<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$sql_coupon = "SELECT * FROM coupon_code";
$result_coupon = $conn->query($sql_coupon);

$sql_user = "SELECT * FROM user_ac";
$result_user = $conn->query($sql_user);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';
    if ($user_id && $coupon_id) {
        $check_sql = "SELECT * FROM applied_coupon WHERE user_id='$user_id' AND coupon_id='$coupon_id'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows == 0) {
            $sql = "INSERT INTO applied_coupon (user_id, coupon_id) VALUES ('$user_id', '$coupon_id')";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['applicable_coupon_msg'] = "applicable coupon added successfully!";
                echo "<script>window.location.href='admin_applied_coupon.php';</script>";
                exit;
            } else {
                echo "Error adding applied-coupon: " . $conn->error;
            }
        } else {
            $_SESSION['combo_exist'] = "applied_coupon combination already exists";
        }
    }
}
?>

<?php loadPartial("admin_header"); ?>

<?php if (!empty($_SESSION['combo_exist'])) : ?>
    <?php echo admin_warning_message($_SESSION['combo_exist']); ?>
    <?php unset($_SESSION['combo_exist']); ?>
<?php endif; ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Add a Tag on a product</h2>
    <form action="admin_applied_coupon_add.php" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl"
          method="post">

        <div class="mb-5">
            <label for="coupon_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Coupon</label>
            <select id="coupon_id" name="coupon_id" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select a coupon</option>
                <?php while ($coupon_row = mysqli_fetch_assoc($result_coupon)) : ?>
                    <option value="<?= $coupon_row['id'] ?>"><?= $coupon_row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-5">
            <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User</label>
            <select id="user_id" name="user_id" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select a user</option>
                <?php while ($user_row = mysqli_fetch_assoc($result_user)) : ?>
                    <option value="<?= $user_row['id'] ?>"><?= $user_row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
        </button>
    </form>
</div>

<?php loadPartial("admin_footer"); ?>

