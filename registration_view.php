<?php
// Include helper functions and database connection
require 'helpers.php';
require 'database.php';
loadPartial('header');
global $conn;
$errorMsg = "";
$duplicateEmail = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registerBtn'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    $check_query = "SELECT COUNT(*) AS count FROM user_ac WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $duplicateEmail = "This email is already used!";
    }

    if ($password != $confirmPassword) {
        $errorMsg = "Passwords do not match";
    }
    if (empty($errorMsg) && empty($duplicateEmail)) {
        $sql = "INSERT INTO user_ac (name, email, address, phone, password) VALUES ('$name', '$email', '$address', '$phone', '$password')";

        $result = $conn->query($sql);

        if ($result) {
            $user_id = $conn->insert_id;
            $_SESSION['user'] = array(
                'id' => $user_id,
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'profile_img' => null,
                'password' => $password
            );


            $coupon_sql = "INSERT INTO applied_coupon (user_id, coupon_id)
               SELECT $user_id, id
               FROM coupon_code";
            $query_coupon = $conn->query($coupon_sql);

            $_SESSION['register_msg'] = "Registration successful! You are now logged in.";
            echo "<script>window.location.href='profile.php'</script>";
            exit();
        } else {
            $errorMsg = "Something went wrong while registration";
        }
    }
}
?>

    <div class="min-h-screen flex justify-center items-center">
        <div class="bg-white p-8 rounded shadow-lg w-7/12"> <!-- Adjusted width to 6/12 (50%) -->
            <h2 class="text-2xl font-semibold mb-4 text-center text-orange-500 underline underline-offset-8">USER
                REGISTRATION</h2>
            <form action="registration_view.php" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                    <input type="text" id="name"
                           class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5"
                           name="name" placeholder="John" required/>
                </div>

                <div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" id="email"
                               class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5"
                               placeholder="example@email.com" required name="email"/>
                    </div>
                    <?php if ($duplicateEmail) : ?>
                        <div class="p-2 text-center mt-1 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            <span class="font-medium">Sorry, that email is already registered!</span> Please try using a
                            different one.
                        </div>
                    <?php endif ?>
                </div>
                <div>
                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
                    <input type="text" id="address"
                           class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5"
                           placeholder="Araihazar, Narayangang" required name="address"/>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Phone</label>
                    <input type="text" id="phone"
                           class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5"
                           placeholder="+880123....." required name="phone"/>
                </div>

                <div class="relative">
                    <!-- Password Field with Input Group -->
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <div class="flex items-center relative">
                        <input type="password" id="password"
                               class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5 pr-10"
                               placeholder="Password" required name="password" minlength="6"/>
                        <!-- Lock Icon Button -->
                        <button type="button" onclick="togglePasswordVisibility('password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center justify-center">
                            <i id="passwordToggleIcon" class="fas fa-lock text-orange-500"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <!-- Confirm Password Field with Input Group -->
                    <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-900">Confirm
                        Password</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword"
                               class="bg-slate-50 focus:outline-none border border-orange-300 focus:border-orange-500 text-sm rounded-lg block w-full p-2.5 pr-10"
                               placeholder="Confirm Password" required name="confirmPassword" minlength="6"/>
                        <button type="button" onclick="togglePasswordVisibility('confirmPassword')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="confirmPasswordToggleIcon" class="fas fa-lock text-orange-500"></i>
                        </button>
                    </div>
                    <?php if ($errorMsg) : ?>
                        <div class="p-2 text-center mt-1 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            <span class="font-medium">Password do not match!</span> make sure password and confirm
                            password
                            match.
                        </div>
                    <?php endif ?>
                </div>
                <div>
                    <button type="submit"
                            class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                            name="registerBtn">
                        Register
                    </button>
                </div>
            </form>
            <p class="text-center pt-4">Already have an account? Please <a class="underline text-cyan-700"
                                                                           href="login_view.php">Login</a> here </p>
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
    </script>

<?php loadPartial('footer'); ?>