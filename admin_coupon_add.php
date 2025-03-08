<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $discount = isset($_POST['discount']) ? htmlspecialchars($_POST['discount']) : '';

    if (empty($name) || empty($discount)) {
        $_SESSION['coupon_add_error'] = "Please enter a name and discount for the coupon";
        echo "<script>window.location.href='admin_coupon_add.php';</script>";
        exit;
    }

    $check_query = "SELECT COUNT(*) AS count FROM coupon_code WHERE name = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $_SESSION['coupon_add_error'] = "Coupon name already exists";
        echo "<script>window.location.href='admin_coupon_add.php';</script>";
        exit;
    }

    if ($discount > 0) {
        $sql = "INSERT INTO coupon_code (name, discount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $discount);
        if ($stmt->execute()) {
            $_SESSION['coupon_add_msg'] = "Coupon added successfully!";
            echo "<script>window.location.href='admin_coupon.php'</script>";
            exit;
        } else {
            $_SESSION['coupon_add_error'] = "Error adding coupon: " . $conn->error;
            echo "<script>window.location.href='admin_coupon_add.php'</script>";
            exit;
        }
    } else {
        $_SESSION['discount_error'] = "Discount must be greater than 0";
        echo "<script>window.location.href='admin_coupon_add.php'</script>";
        exit;
    }
}
?>


<?php loadPartial('admin_header'); ?>

<?php if (!empty($_SESSION['coupon_add_error'])): ?>
    <?php echo admin_danger_message($_SESSION['coupon_add_error']); ?>
    <?php unset($_SESSION['coupon_add_error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['discount_error'])): ?>
    <?php echo admin_danger_message($_SESSION['discount_error']); ?>
    <?php unset($_SESSION['discount_error']); ?>
<?php endif; ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">ADD a new Coupon</h2>
    <form action="admin_coupon_add.php" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl" method="post">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required/>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="discount" id="discount"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required/>
            <label for="discount"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Discount</label>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
        </button>
    </form>
</div>

<?php loadPartial('admin_footer'); ?>

