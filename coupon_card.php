<div class="bg-gray-100  p-6 rounded-lg shadow-lg mb-8 ">
    <form method="POST" action="">
        <div class="mb-4 inline">
            <input type="text" id="coupon" name="coupon_code"
                   class="inline w-[75%] px-4 py-2 border border-orange-300 focus:outline-none rounded-lg focus:ring focus:ring-orange-300 focus:border-orange-400"
                   placeholder="Enter your coupon code">
        </div>

        <div class="text-center inline-block w-[12.5%] ml-[12%]">
            <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 focus:outline-none focus:ring focus:ring-orange-500 focus:border-orange-500 w-full"
                    name="coupon_btn">
                Apply Coupon
            </button>

        </div>
    </form>

    <?php if (!empty($_SESSION['coupon_code'])): ?>
        <div class="mt-4 text-green-500 text-center">
            <?= $_SESSION['coupon_code'] ?>
            <?php unset($_SESSION['coupon_code']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['coupon_error'])): ?>
        <div class="mt-4 text-red-500 text-center">
            <?= $_SESSION['coupon_error'] ?>
            <?php unset($_SESSION['coupon_error']) ?>
        </div>
    <?php endif; ?>
</div>