<?php require 'helpers.php'; ?>
<?php require 'database.php'; ?>
<?php loadPartial('header'); ?>

<?php
global $conn;
$errorMsg = "";

$next_url = "";
if (isset($_GET['next'])) {
    $next_url = $_GET['next'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loginBtn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_ac WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        $_SESSION['user'] = $user;
        if (!empty($next_url)) {
            $_SESSION['login_msg'] = "You are logged in successfully!.";
            echo "<script>window.location.href='$next_url'</script>";
        } else {
            $_SESSION['login_msg'] = "You are logged in successfully!.";
            echo "<script>window.location.href='profile.php'</script>";
        }
        exit();
    } else {
        $errorMsg = "Email or password is incorrect";
    }
}
?>
<?php if (!empty($_SESSION['login_msg'])) : ?>
    <?= danger_message($_SESSION['login_msg']) ?>
    <?php unset($_SESSION['login_msg']) ?>
<?php elseif (!empty($_SESSION['logout_msg'])): ?>
    <?php echo warning_message($_SESSION['logout_msg']) ?>
    <?php unset($_SESSION['logout_msg']) ?>
<?php endif; ?>

<div class="min-h-96 flex justify-center items-center">
    <div class="bg-white p-8 rounded shadow-lg w-7/12">
        <h2 class="text-2xl font-semibold mb-4 text-center text-orange-500 underline underline-offset-8">USER LOGIN</h2>
        <form action="login_view.php<?php if (!empty($next_url)) echo "?next=$next_url"; ?>" method="POST"
              class="space-y-4"> <!-- Change action to your login script -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" id="email"
                       class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5"
                       placeholder="example@email.com" required name="email"/>
            </div>
            <div class="relative">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <div class="flex items-center relative">
                    <input type="password" id="password"
                           class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5 pr-10"
                           placeholder="Password" required name="password"/>
                    <button type="button" onclick="togglePasswordVisibility('password')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="passwordToggleIcon" class="fas fa-lock text-orange-500"></i>
                    </button>
                </div>
            </div>
            <?php if ($errorMsg) : ?>
                <div class="p-2 text-center mt-1 text-sm text-red-800 rounded-lg bg-red-50"
                     role="alert">
                    <span class="font-medium">Password do not match!</span> make sure password and confirm password
                    match.
                </div>
            <?php endif ?>
            <div>
                <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                        name="loginBtn">
                    Login
                </button>
            </div>
        </form>
        <p class="text-center pt-4">Don't have an account? Please <a class="underline text-cyan-700"
                                                                     href="registration_view.php">Register</a> here </p>
    </div>
</div>

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

    const closeAlertBtn = document.querySelector('#closeAlertBtn');
    const alert = document.querySelector('#alert');
    closeAlertBtn.addEventListener('click', () => {
        alert.style.display = 'none';
    });

</script>

<?php loadPartial('footer'); ?>
