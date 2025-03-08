<?php require "helpers.php"?>
<?php loadPartial("header"); ?>
<div class="bg-gradient-to-br from-orange-400 to-red-500 min-h-screen flex flex-col justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full mx-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 mx-auto" viewBox="0 0 20 20"
             fill="currentColor">
            <path fill-rule="evenodd"
                  d="M10 19a9 9 0 100-18 9 9 0 000 18zm-1.414-3.586l6-6-1.414-1.414-5.293 5.293-2.293-2.293L5 12.293 8.293 15.5l.707-.707z"
                  clip-rule="evenodd"/>
        </svg>
        <h2 class="text-3xl font-semibold text-center text-gray-800 mt-4">Payment Successful!</h2>
        <p class="text-lg text-center text-gray-600 mt-2">Your payment has been processed successfully.</p>
        <div class="flex justify-center mt-6">
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md mr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-300 ease-in-out transform hover:scale-105">Continue Shopping</a>
        </div>
    </div>
</div>

<?php loadPartial("footer"); ?>
