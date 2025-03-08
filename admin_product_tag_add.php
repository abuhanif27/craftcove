<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$sql_tag = "SELECT * FROM tag";
$result_tag = $conn->query($sql_tag);

$sql_product = "SELECT * FROM product";
$result_product = $conn->query($sql_product);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tag_id = isset($_POST['tag_id']) ? $_POST['tag_id'] : '';
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    if ($tag_id && $product_id) {
        $check_sql = "SELECT * FROM tag_product WHERE tag_id='$tag_id' AND product_id='$product_id'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows == 0) {
            $sql = "INSERT INTO tag_product (tag_id, product_id) VALUES ('$tag_id', '$product_id')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Tag-Product added successfully');</script>";
                echo "<script>window.location.href='admin_tag_product.php';</script>";
                exit;
            } else {
                echo "Error adding Tag-Product: " . $conn->error;
            }
        } else {
            echo "<script>alert('Tag-Product combination already exists');</script>";
        }
    }
}
?>

<?php loadPartial("admin_header"); ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Add a Tag on a product</h2>
    <form action="admin_product_tag_add.php" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl"
          method="post">

        <div class="mb-5">
            <label for="tag" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tag</label>
            <select id="tag" name="tag_id" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select a tag</option>
                <?php while ($tag_row = mysqli_fetch_assoc($result_tag)) : ?>
                    <option value="<?= $tag_row['id'] ?>"><?= $tag_row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-5">
            <label for="product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product</label>
            <select id="product" name="product_id" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select a product</option>
                <?php while ($product_row = mysqli_fetch_assoc($result_product)) : ?>
                    <option value="<?= $product_row['id'] ?>"><?= $product_row['name'] ?></option>
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

