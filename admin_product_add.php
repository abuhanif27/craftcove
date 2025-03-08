<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$categoryQuery = "SELECT * FROM category";
$categoryResult = $conn->query($categoryQuery);
$categories = [];
if ($categoryResult->num_rows > 0) {
    while ($categoryRow = $categoryResult->fetch_assoc()) {
        $categories[] = $categoryRow;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';
    $category_id = isset($_POST['category']) ? $_POST['category'] : '';

    $img_url = null;
    if ($_FILES['img_url']['name']) {
        $image_name = $_FILES['img_url']['name'];
        $image_tmp = $_FILES['img_url']['tmp_name'];
        $image_path = 'image/' . $image_name;

        if (!is_dir('image')) {
            mkdir('image', 0777, true);
        }

        if (move_uploaded_file($image_tmp, $image_path)) {
            $img_url = $image_path;
        } else {
            $_SESSION['product_img_upload_error'] = "Error uploading image";
            exit;
        }
    }

    if ($price < 0 || $stock < 0) {
        $_SESSION['product_insert_negative_error'] = "Negative values are not allowed for price or stock";
    } else {
        if (!isset($_SESSION['product_insert_negative_error'])) {
            $sql = "INSERT INTO product (name, description, price, stock, img_url, category_id) VALUES ('$name', '$description', '$price', '$stock', '$img_url', '$category_id')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['product_add_msg'] = "Product inserted successfully";
                echo "<script>window.location.href='admin_product.php';</script>";
                exit;
            } else {
                $_SESSION['product_insert_error_msg'] = "Error while inserting product: " . $conn->error;
            }
        }
    }
}
?>

<?php loadPartial("admin_header"); ?>

<?php if (!empty($_SESSION['product_insert_error_msg'])): ?>
    <?= admin_danger_message($_SESSION['product_insert_error_msg_msg']) ?>
    <?php unset($_SESSION['product_insert_error_msg']) ?>
<?php endif; ?>
<?php if (!empty($_SESSION['product_insert_negative_error'])): ?>
    <?= admin_danger_message($_SESSION['product_insert_negative_error']) ?>
    <?php unset($_SESSION['product_insert_negative_error']) ?>
<?php endif; ?>
<?php if (!empty($_SESSION['product_img_upload_error'])): ?>
    <?= admin_danger_message($_SESSION['product_img_upload_error']) ?>
    <?php unset($_SESSION['product_img_upload_error']) ?>
<?php endif; ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">ADD a new product</h2>
    <form action="admin_product_add.php" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl" method="post"
          enctype="multipart/form-data">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required/>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product
                name</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <textarea name="description" id="description"
                      class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                      placeholder=" " required> </textarea>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Description</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="price" id="price" step="any"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required/>
            <label for="price"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="stock" id="stock"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required/>
            <label for="stock"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Stock</label>
        </div>
        <div class="mb-5">
            <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400" for="img_url">Image</label>
            <input class="block w-full mb-5 text-sm py-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 appearance-none focus:outline-none bg-transparent dark:border-gray-600 dark:placeholder-gray-400"
                   id="img_url" type="file" name="img_url">
        </div>

        <div class="mb-5">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="category" name="category" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Submit
        </button>
    </form>
</div>

<?php loadPartial("admin_footer"); ?>
