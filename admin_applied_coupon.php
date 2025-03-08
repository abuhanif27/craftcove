<?php require 'helpers.php'; ?>
<?php require 'database.php'; ?>
<?php loadPartial('admin_header'); ?>
<?php
global $conn;

admin_authentication();

$sql = "SELECT ac.id as id, u.name as user, cc.name as coupon, applied
FROM applied_coupon ac
         JOIN user_ac u ON ac.user_id = u.id
         JOIN coupon_code cc ON cc.id = ac.coupon_id order by id desc";
$result = $conn->query($sql);
?>

<?php if (!empty($_SESSION['applied_coupon_delete_msg'])) : ?>
    <?php echo admin_success_message($_SESSION['applied_coupon_delete_msg']); ?>
    <?php unset($_SESSION['applied_coupon_delete_msg']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['applicable_coupon_msg'])): ?>
    <?php echo admin_success_message($_SESSION['applicable_coupon_msg']) ?>
    <?php unset($_SESSION['applicable_coupon_msg']) ?>
<?php endif; ?>

<div class="container mt-5">
    <h1 class="text-gray-300 text-2xl tracking-wide uppercase text-center font-semibold underline underline-offset-8 mb-8">
        Manage Applied-coupon</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Coupon
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
                <th scope="col" class="px-6 py-3">
                    Applied
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        #<?= $row['id'] ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= $row['coupon'] ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $row['user'] ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $row['applied'] == 0 ? "❌" : "✔" ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="admin_applied_coupon_delete.php?id=<?= $row['id'] ?>"
                           class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_applied_coupon_add.php"
           class="text-white bg-gray-700 w-full block uppercase text-center py-2 tracking-wider"><i
                    class="fa-solid fa-plus"></i> Add applicable coupon</a>
    </div>
</div>
<?php loadPartial('admin_footer'); ?>


