<?php require 'helpers.php'; ?>
<?php require 'database.php'; ?>
<?php loadPartial('admin_header'); ?>
<?php
global $conn;

admin_authentication();

$sql = "SELECT r.id as id, p.name as product, u.name as user, comment
FROM review r
         JOIN product p ON r.product_id = p.id
         JOIN user_ac u ON u.id = r.user_id order by r.created_at DESC";
$result = $conn->query($sql);
?>


<?php if (!empty($_SESSION['review_delete_msg'])): ?>
    <?php echo admin_success_message($_SESSION['review_delete_msg']) ?>
    <?php unset($_SESSION['review_delete_msg']); ?>
<?php endif; ?>

<div class="container mt-5">
    <h1 class="text-gray-300 text-2xl tracking-wide uppercase text-center font-semibold underline underline-offset-8 mb-8">
        Manage Review</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Comment
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
                <th scope="col" class="px-6 py-3">
                    Product
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
                        <?= $row['comment'] ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= $row['user'] ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $row['product'] ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="admin_review_delete.php?id=<?= $row['id'] ?>"
                           class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php loadPartial('admin_footer'); ?>

