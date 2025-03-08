<?php
require "helpers.php";
require "database.php";
global $conn;

$productsPerPage = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $productsPerPage;

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$filterPriceRange = isset($_GET['priceRange']) ? $_GET['priceRange'] : '';

$sql = "SELECT p.*, c.name AS category_name FROM product p JOIN category c ON p.category_id=c.id";
$whereClause = [];

if (!empty($searchQuery)) {
    $whereClause[] = "p.name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%'";
}

if (!empty($filterCategory)) {
    $whereClause[] = "c.name = '" . mysqli_real_escape_string($conn, $filterCategory) . "'";
}

if (!empty($filterPriceRange)) {
    $priceRange = explode('-', $filterPriceRange);
    if (count($priceRange) == 2) {
        $minPrice = (float)$priceRange[0];
        $maxPrice = (float)$priceRange[1];
        $whereClause[] = "p.price BETWEEN $minPrice AND $maxPrice";
    }
}

if (!empty($whereClause)) {
    $sql .= " WHERE " . implode(' AND ', $whereClause);
}

$sql .= " LIMIT $offset, $productsPerPage";

$result = mysqli_query($conn, $sql);
$num = $result->num_rows;

if (!$result) {
    echo "Error: " . mysqli_error($conn);
}

$totalProducts = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM product"));
$totalPages = ceil($totalProducts / $productsPerPage);

loadPartial('header');
?>

<div class=" pb-8 bg-stone-100">
    <div class="container mx-auto w-[80%]">
        <form class="flex justify-center" method="GET">
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="search" id="search"
                       class="block py-2.5 px-0 w-full text-sm text-slate-600 bg-transparent border-0 border-b-2 border-orange-300 appearance-none focus:outline-none focus:ring-0 focus:border-orange-400 peer"
                       placeholder=" " value="<?= $searchQuery ?>"/>
                <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </button>
                <label for="search"
                       class="peer-focus:font-medium absolute text-sm text-gray-300 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-orange-400 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Search...</label>
            </div>
        </form>
    </div>
</div>

<div class="">
    <div class="flex flex-wrap">
        <div class="w-full md:w-1/4 bg-gray-100 pr-8 flex flex-col space-y-5">
            <h2 class="text-gray-800 text-2xl font-semibold mb-4 text-center uppercase underline underline-offset-8 text-orange-500">
                Filter
            </h2>
            <form id="filterForm" action="" method="GET">

                <div class="mb-6">
                    <label for="priceRange" class="block mb-2 text-sm font-medium text-gray-600">Select
                        a price range</label>
                    <select id="priceRange" name="priceRange"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 focus:outline-none block w-full p-2.5">
                        <option value="" <?php echo $filterPriceRange === '' ? 'selected' : ''; ?>>Any price range
                        </option>
                        <option value="0-10" <?php echo $filterPriceRange === '0-10' ? 'selected' : ''; ?>>$0 -
                            $10
                        </option>
                        <option value="10-20" <?php echo $filterPriceRange === '10-20' ? 'selected' : ''; ?>>$10 -
                            $20
                        </option>
                        <option value="20-30" <?php echo $filterPriceRange === '20-30' ? 'selected' : ''; ?>>$20 -
                            $30
                        </option>
                        <option value="30-40" <?php echo $filterPriceRange === '30-40' ? 'selected' : ''; ?>>$30 -
                            $40
                        </option>
                        <option value="40-100000" <?php echo $filterPriceRange === '40-100000' ? 'selected' : ''; ?>
                        <span>&gt; $40</span></option>

                    </select>

                </div>


                <div class="mb-6">
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-600">Select
                        a category</label>
                    <select id="category" name="category"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 focus:outline-none block w-full p-2.5">
                        <option value="" <?php echo $filterCategory === '' ? 'selected' : ''; ?>>All categories</option>
                        <?php
                        $categories = mysqli_query($conn, "SELECT DISTINCT c.name FROM product p JOIN category c ON p.category_id=c.id");
                        while ($row = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $row['name'] ?>"
                                <?php echo $filterCategory === $row['name'] ? 'selected' : ''; ?>>
                                <?= $row['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button id="filter_btn" type="submit"
                        class="block w-full bg-orange-400 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring focus:ring-blue-200 focus:ring-opacity-50 uppercase text-sm tracking-wider">
                    Apply Filter
                </button>
            </form>
        </div>
        <div class="w-full md:w-3/4">

            <?php if (mysqli_num_rows($result) > 0) : ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3">
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
                                        <div class="font-bold text-md text-left tracking-wider"><?php echo $row['name']; ?></div>
                                        <div class="mx-3">|</div>
                                        <div class="text-slate-600 font-semibold text-lg"><?php echo '$' . $row['price']; ?></div>
                                    </div>
                                    <p class="text-gray-700 text-base text-sm">
                                        <?php echo substr($row['description'], 0, 70) . ' ...'; ?>
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

                <?php if (($totalPages > 1 && $productsPerPage > 5) && (empty($searchQuery) && empty($filterCategory) && empty($filterPriceRange))) : ?>
                    <div class="flex justify-center mt-8" id="pagination">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                             aria-label="Pagination">
                            <?php if ($page > 1) : ?>
                                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchQuery; ?>&category=<?php echo $filterCategory; ?>&priceRange=<?php echo $filterPriceRange; ?>"
                                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <a href="?page=<?php echo $i; ?>&search=<?php echo $searchQuery; ?>&category=<?php echo $filterCategory; ?>&priceRange=<?php echo $filterPriceRange; ?>"
                                   class="<?php echo $i == $page ? 'bg-orange-500 text-white' : 'bg-white text-orange-500'; ?> relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium hover:bg-gray-50 hover:text-orange-500">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages) : ?>
                                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchQuery; ?>&category=<?php echo $filterCategory; ?>&priceRange=<?php echo $filterPriceRange; ?>"
                                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    Next
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <p class="text-center text-gray-800 font-semibold">No products match your search.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($num > 5): ?>
    <?php loadPartial('payment_info') ?>
<?php endif; ?>

<?php loadPartial('footer'); ?>
