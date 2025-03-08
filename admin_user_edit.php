<?php
require "helpers.php";
require "database.php";

global $conn;
admin_authentication();
// Check if product ID is provided and valid
$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id || !is_numeric($id)) {
    echo "<script>window.location.href='admin_user.php';</script>";
    exit; // Exit to prevent further execution
}

// Fetch the product data
$sql = "SELECT * FROM user_ac WHERE id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>window.location.href='admin_user.php';</script>";
    exit; // Exit if product not found
}
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $profile_img = isset($_POST['profile_img']) ? $_POST['profile_img'] : '';

    $sql = "UPDATE user_ac SET name='$name', email='$email', address='$address', phone='$phone', password='$password'";

    if ($_FILES['profile_img']['name']) {
        $image_name = $_FILES['profile_img']['name'];

        if (!file_exists('image')) {
            mkdir('image');
        }

        $image_path = 'image/' . $image_name;
        move_uploaded_file($_FILES['profile_img']['tmp_name'], $image_path);

        $sql .= ", profile_img='$image_path'";
    }

    $sql .= " WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['user_profile_update_msg'] = "profile updated successfully";
        echo "<script>window.location.href='admin_user.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error while updating User: " . $conn->error . "</div>";
    }

}
?>

<?php loadPartial("admin_header"); ?>

<div class="flex min-h-screen items-center mt-4 flex-col">
    <h2 class="text-slate-300 text-xl uppercase underline underline-offset-8 mb-8">Edit User "<?= $row['name'] ?>
        "</h2>
    <form action="admin_user_edit.php?id=<?= $id ?>" class="w-[65%] mx-auto bg-slate-700 px-10 py-8 rounded-2xl"
          method="post"
          enctype="multipart/form-data">
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="name" id="name"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['name'] ?>"/>
            <label for="name"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Full
                name</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="email" name="email" id="email"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['email'] ?>"/>
            <label for="email"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
        </div>

        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="address" id="address"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['address'] ?>"/>
            <label for="address"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Address</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="text" name="phone" id="phone"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " required value="<?= $row['phone'] ?>"/>
            <label for="phone"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="password" name="password" id="password"
                   class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                   placeholder=" " value="<?= $row['password'] ?>" required/>
            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
        <i id="passwordToggleIcon" class="fas fa-lock cursor-pointer"
           onclick="togglePasswordVisibility('password')"></i>
    </span>
            <label for="password"
                   class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
        </div>
        <div class="relative z-0 w-full mb-5 group">
            <input type="hidden" name="current_img_url" value="<?= htmlspecialchars($row['profile_img']) ?>">
            <p class="text-xs pb-1"><?= htmlspecialchars($row['profile_img']) ?></p>
            <input class="block w-full mb-5 text-sm py-1 text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 appearance-none focus:outline-none bg-transparent dark:border-gray-600 dark:placeholder-gray-400"
                   id="profile_img" type="file" name="profile_img">
        </div>
        <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Update
        </button>
    </form>
</div>

<?php loadPartial("admin_footer"); ?>


<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + 'ToggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-lock');
            icon.classList.add('fa-lock-open');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-lock-open');
            icon.classList.add('fa-lock');
        }
    }
</script>