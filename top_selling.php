<?php
require "helpers.php";
require "database.php";
global $conn;
$sql = "SELECT * FROM product order by total_sold desc LIMIT 6";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

loadPartial('header');
?>

<div class="container mx-auto">
    <h2 class="text-2xl font-semibold text-center text-orange-500 underline underline-offset-8 mb-5">TOP SELLING</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <!-- Product Card -->
            <div class="bg-white rounded-lg shadow-2xl flex flex-col justify-between">
                <?php if ($row['img_url']) : ?>
                    <div>
                        <img class="object-cover w-full h-64 rounded-t-lg hover:scale-95 duration-200 "
                             src="<?= $row['img_url'] ?>"
                             alt="Product demo">
                    </div>
                <?php else: ?>
                    <div>
                        <img class="object-cover w-full h-64 rounded-t-lg hover:scale-95 duration-200 "
                             src="default.jpeg"
                             alt="Product demo">
                    </div>
                <?php endif; ?>
                <div class="px-3 py-4">
                    <div class="flex flex-col space-y-3">
                        <div class="flex justify-between items-center">
                            <div class="font-bold text-md text-left tracking-wider uppercase"><?php echo $row['name']; ?></div>
                            <div class="mx-3">|</div>
                            <div class="text-slate-600 font-semibold text-lg"><?php echo '$' . $row['price']; ?></div>
                        </div>
                        <p class="text-gray-700 text-base text-md">
                            <?php echo substr($row['description'], 0, 80) . ' ...'; ?>
                        </p>
                        <?php if (!empty($row['category_name'])) : ?>
                            <div class="text-sm"><span
                                        class="font-semibold mr-1">Category:</span> <?php echo $row['category_name']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <a href="product_view.php?id=<?= $row['id'] ?>"
                           class="bg-orange-400 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-full view-btn">
                            View
                        </a>
                        <?php if ($row['stock'] > 0): ?>
                            <span class="text-gray-600">In Stock</span>
                        <?php else: ?>
                            <span class="text-red-600">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php loadPartial('footer'); ?>
