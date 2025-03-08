<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id || !is_numeric($id)) {
    echo "<script>window.location.href='admin_product.php';</script>";
    exit;
}

$sql = "SELECT * FROM product WHERE id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>window.location.href='admin_product.php';</script>";
    exit;
}
$row = $result->fetch_assoc();

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
    $total_sold = isset($_POST['total_sold']) ? $_POST['total_sold'] : '';
    $img_url = isset($_POST['img_url']) ? $_POST['img_url'] : '';
    $category_id = isset($_POST['category']) ? $_POST['category'] : '';

    if ($price < 0 || $stock < 0 || $total_sold < 0) {
        $_SESSION['negative_quantity_error'] = "Negative values are not allowed for price, stock, or total sold";
    } else {
        $sql = "UPDATE product SET name='$name', description='$description', price='$price', stock='$stock', total_sold='$total_sold', category_id='$category_id'";

        if ($_FILES['img_url']['name']) {
            $image_name = $_FILES['img_url']['name'];

            if (!file_exists('image')) {
                mkdir('image');
            }

            $image_path = 'image/' . $image_name;
            move_uploaded_file($_FILES['img_url']['tmp_name'], $image_path);

            $sql .= ", img_url='$image_path'";
        }

        $sql .= " WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['product_update_msg'] = "Product updated successfully!";
            echo "<script>window.location.href='admin_product.php';</script>";
            exit;
        } else {
            echo admin_danger_message("Error while updating product: " . $conn->error);
        }
    }
}

$cat_sql = "SELECT DISTINCT c.id as category_id,c.name as category_name FROM product p JOIN category c ON p.category_id=c.id WHERE p.id = '$id'";
$cat_result = $conn->query($cat_sql);
$catRow = $cat_result->fetch_assoc();

?>


<?php loadPartial("admin_header"); ?>

<?php if (!empty($_SESSION['negative_quantity_error'])): ?>
    <?php echo admin_danger_message($_SESSION['negative_quantity_error']) ?>
    <?php unset($_SESSION['negative_quantity_error']) ?>
<?php endif; ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Edit product "<?= $row['name'] ?>
        "</h2>
    <form action="admin_product_edit.php?id=<?= $id ?>" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl"
          method="post"
          enctype="multipart/form-data">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['name'] ?>"/>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Product
                name</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <textarea name="description" id="description"
                      class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                      placeholder=" " required> <?= $row['description'] ?> </textarea>
            <label for="description"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Description</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="price" id="price" step="any"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['price'] ?>"/>
            <label for="price"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Price</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="stock" id="stock"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['stock'] ?>"/>
            <label for="stock"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Stock</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="number" name="total_sold" id="total_sold"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " value="<?= $row['total_sold'] ?>" required/>
            <label for="total_sold"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Total
                Sold</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <!-- Hidden input field to store the current img_url -->
            <input type="hidden" name="current_img_url" value="<?= htmlspecialchars($row['img_url']) ?>">
            <!-- Display the current img_url as text -->
            <p class="text-xs pb-1"><?= htmlspecialchars($row['img_url']) ?></p>
            <!-- File input for uploading new image -->
            <input class="block w-full mb-5 text-sm py-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 appearance-none focus:outline-none bg-transparent dark:border-gray-600 dark:placeholder-gray-400"
                   id="img_url" type="file" name="img_url">
        </div>
        <div class="mb-5">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="category" name="category" required
                    class="bg-transparent border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="<?= $catRow['category_id'] ?>"><?= $catRow['category_name'] ?></option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update
        </button>
    </form>
</div>


<?php loadPartial("admin_footer"); ?>
