<?php
require "helpers.php";
require "database.php";
$success_msg = "";
$error_msg = "";
global $conn;

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id || !is_numeric($id)) {
    echo "<script>window.location.href='404.php';</script>";
    exit;
}

$sql = "SELECT * FROM user_ac WHERE id = '$id'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "<script>window.location.href='404.php';</script>";
    exit;
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
        $success_msg = "Profile updated successfully";
        
        // Update the session data with the new user information
        $updated_user_sql = "SELECT * FROM user_ac WHERE id = '$id'";
        $updated_user_result = $conn->query($updated_user_sql);
        if ($updated_user_result->num_rows > 0) {
            $updated_user = $updated_user_result->fetch_assoc();
            $_SESSION['user'] = $updated_user;
        }
    } else {
        $error_msg = "Error updating profile: " . $conn->error;
//        echo "<div class='alert alert-danger' role='alert'>Error while updating User: " . $conn->error . "</div>";
    }
}
?>

<?php loadPartial("header"); ?>

<div class="container py-5">
    <?php if (!empty($success_msg)): ?>
        <?= success_message($success_msg) ?>
    <?php elseif (!empty($error_msg)): ?>
        <?= danger_message($error_msg) ?>
    <?php endif; ?>
    <div class="flex justify-center">
        <div class="w-full sm:w-8/12">
            <div class="bg-white shadow-sm p-6 rounded-lg">
                <h2 class="text-center text-2xl mb-4 font-medium text-orange-500 underline underline-offset-8 uppercase tracking-wide">
                    Edit
                    Profile</h2>
                <form action="user_profile_edit.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" id="name"
                               class="w-full focus:outline-none border border-orange-200 p-2 rounded-md focus:border-orange-400"
                               value="<?= htmlspecialchars($row['name']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                               class="w-full focus:outline-none border border-orange-200 p-2 rounded-md focus:border-orange-400"
                               value="<?= htmlspecialchars($row['email']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" id="address"
                               class="w-full focus:outline-none border border-orange-200 p-2 rounded-md focus:border-orange-400"
                               value="<?= htmlspecialchars($row['address']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone"
                               class="w-full focus:outline-none border border-orange-200 p-2 rounded-md focus:border-orange-400"
                               value="<?= htmlspecialchars($row['phone']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password"
                                   class="w-full focus:outline-none border border-orange-200 p-2 rounded-md focus:border-orange-400 pr-12"
                                   placeholder="Password" required name="password"
                                   value="<?= htmlspecialchars($row['password']) ?>">
                            <button type="button" onclick="togglePasswordVisibility('password')"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600">
                                <i id="passwordToggleIcon" class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="profile_img" class="block text-sm font-medium text-gray-700 mb-2">Profile
                            Picture</label>
                        <input type="hidden" name="current_img_url"
                               value="<?= htmlspecialchars($row['profile_img']) ?>">
                        <p class="mb-2 text-sm text-cyan-500"><?= htmlspecialchars($row['profile_img']) ?></p>
                        <input type="file" name="profile_img" id="profile_img"
                               class="w-full focus:outline-none border border-orange-200 p-1 rounded-md focus:border-orange-400"
                               accept="image/*">
                    </div>
                    <div class="text-center">
                        <button type="submit"
                                class="bg-orange-400 text-white py-2 rounded-lg font-medium w-full hover:bg-orange-500">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php loadPartial("footer"); ?>

<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + 'ToggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    const closeAlertBtn = document.querySelector('#closeAlertBtn');
    const alert = document.querySelector('#alert');
    if (closeAlertBtn && alert) {
        closeAlertBtn.addEventListener('click', () => {
            alert.style.display = 'none';
            window.location.href = "profile.php";
        });
    }
</script>
