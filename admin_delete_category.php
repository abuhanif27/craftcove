<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$category_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$category_id || !is_numeric($category_id)) {
    echo "<script>window.location.href='admin_category.php';</script>";
    exit;
}

$sql = "SELECT * FROM category WHERE id = $category_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>alert('Category not found');</script>";
    echo "<script>window.location.href='admin_category.php';</script>";
    exit;
}
$row = $result->fetch_assoc();

if (isset($_POST['confirm'])) {
    try {
        $sql_delete = "DELETE FROM category WHERE id = $category_id";
        if ($conn->query($sql_delete) === TRUE) {
            $_SESSION['category_delete_msg'] = "Category Delete successful!";
            echo "<script>window.location.href='admin_category.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error deleting category: " . $conn->error . "');</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint')) {
            echo "<script>alert('Error deleting category: Cannot delete or update a parent row due to a foreign key constraint');</script>";
        } else {
            echo "<script>alert('Error deleting category: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<?php loadPartial("admin_header"); ?>
<div class="w-[65%] mt-8 mx-auto p-12 bg-white border border-gray-200 rounded-3xl shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-2xl font-semibold font-mono tracking-tight text-gray-900 dark:text-white text-center">Are you
        sure you want to delete "<?= $row['name'] ?>"?</h5>
    <form action="admin_delete_category.php?id=<?= $category_id ?>" method="post" class="flex justify-between mt-8">
        <a href="admin_category.php"
           class="inline-flex space-x-2 items-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
            <i class="fa-solid fa-arrow-left"></i>
            <span class="pr-3">Back</span>
        </a>
        <button type="submit" name="confirm"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            <span class="px-2">Confirm</span>
        </button>
    </form>
</div>
<?php loadPartial("admin_footer"); ?>
