<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id || !is_numeric($id)) {
    echo "<script>window.location.href='admin_cart_order.php';</script>";
    exit;
}

$sql = "SELECT c.id as order_id, p.name as product_name, c.status FROM cart c JOIN product p ON c.product_id=p.id WHERE c.id=$id";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>window.location.href='admin_cart_order.php';</script>";
    exit;
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    if ($status) {
        $sql = "UPDATE cart SET status='$status' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['order_cart_update_msg']= "Order updated successfully";
            echo "<script>window.location.href='admin_cart_order.php';</script>";
            exit;
        } else {
            echo "Error updating order: " . $conn->error;
        }
    }
}

$old_status = $row['status'];
?>

<?php loadPartial("admin_header"); ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Edit Order-Cart</h2>
    <form action="admin_cart_order_edit.php?id=<?= $id ?>" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl"
          method="post">

        <div class="mb-5">
            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product</label>
            <select id="status" name="status" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="<?= $row['status'] ?>"><?= $row['status'] ?></option>
                <?php if ($old_status != 'pending') : ?>
                <option value="pending">Pending</option>
                <?php endif; ?>

                <?php if ($old_status != 'process') : ?>
                <option value="process">Processing</option>
                <?php endif;?>

                <?php if ($old_status != 'complete') : ?>
                <option value="complete">Complete</option>
                <?php endif; ?>
            </select>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update
        </button>
    </form>
</div>


<?php loadPartial("admin_footer"); ?>
