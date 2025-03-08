<?php require "helpers.php" ?>
<?php require "database.php" ?>
<?php loadPartial('admin_header'); ?>
<?php
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
    echo "<script>window.location.href='admin_tag.php';</script>";
    exit;
}
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    if ($name) {
        $sql = "UPDATE tag SET name='$name' WHERE id=$tag_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['tag_update_msg']= "Tag updated successfully!";
            echo "<script>window.location.href='admin_tag.php';</script>";
            exit;
        } else {
            echo "Error updating category: " . $conn->error;
        }
    }
}

?>
<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Edit Tag "<?= $row['name'] ?>"</h2>
    <form action="admin_tag_edit.php?id=<?= $row['id'] ?>"
          class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl" method="post">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['name'] ?>"/>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tag
                name</label>
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update
        </button>
    </form>
</div>


<?php loadPartial('admin_footer'); ?>
