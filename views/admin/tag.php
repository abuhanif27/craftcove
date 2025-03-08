<?php require "../../helpers.php" ?>
<?php loadPartial("header"); ?>
<form action="" class="max-w-md mx-auto">
    <div class="mb-6">
        <label for="label" class="block text-gray-700 text-sm font-bold mb-2">Label</label>
        <input type="text" id="label" name="label"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Add Label
        </button>
    </div>
</form>
<?php loadPartial("footer"); ?>
