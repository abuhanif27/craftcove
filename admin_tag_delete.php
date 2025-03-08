<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
$tag_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$tag_id || !is_numeric($tag_id)) {
    echo "<script>window.location.href='admin_tag.php';</script>";
    exit;
}

$sql = "SELECT * FROM tag WHERE id = $tag_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>alert('Tag not found');</script>";
    echo "<script>window.location.href='admin_tag.php';</script>";
    exit;
}
$row = $result->fetch_assoc();

if (isset($_POST['confirm'])) {
    try {
        $sql_delete = "DELETE FROM tag WHERE id = $tag_id";
        if ($conn->query($sql_delete) === TRUE) {
            $_SESSION['tag_delete_msg'] = "Tag Delete successful!";
            echo "<script>window.location.href='admin_tag.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error deleting Tag: " . $conn->error . "');</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint')) {
            echo "<script>alert('Error deleting Tag: Cannot delete or update a parent row due to a foreign key constraint');</script>";
        } else {
            echo "<script>alert('Error deleting Tag: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<?php loadPartial("admin_header"); ?>

<div class="w-[65%] mt-8 mx-auto p-12 bg-white border border-gray-200 rounded-3xl shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-2xl font-semibold font-mono tracking-tight text-gray-900 dark:text-white text-center">Are you
        sure you want to delete "<?= $row['name'] ?>"?</h5>
    <form action="admin_tag_delete.php?id=<?= $tag_id ?>" method="post" class="flex justify-between mt-8">
        <a href="admin_tag.php"
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

