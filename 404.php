<?php require "helpers.php" ?>
<?php loadPartial("header"); ?>
<div class="bg-gray-100 h-96 flex flex-col justify-center items-center">
<div class="max-w-md mx-auto text-center">
    <div class="flex justify-center mb-6"> <!-- Added flex container for centering -->
        <svg class="w-20 h-20 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">404 - Page Not Found</h1>
    <p class="text-gray-600 mb-6">Oops! The page you are looking for could not be found.</p>
    <a href="/" class="bg-red-500 text-white py-2 px-6 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-300">Go Home</a>
</div>
</div>
<?php loadPartial("footer"); ?>
