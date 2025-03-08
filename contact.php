<?php require 'helpers.php'; ?>
<?php loadPartial('header'); ?>

<section class="bg-white py-20">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-4xl font-bold mb-8">Contact Us</h1>
        <p class="text-lg leading-relaxed mb-8">Have a question or feedback? We'd love to hear from you!</p>
        <form action="contact_process.php" method="POST" class="max-w-lg mx-auto">
            <div class="mb-4">
                <label for="name" class="block text-lg mb-2">Your Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-lg mb-2">Your Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="message" class="block text-lg mb-2">Message</label>
                <textarea id="message" name="message" rows="5" class="w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Send Message</button>
        </form>
    </div>
</section>

<?php loadPartial('footer'); ?>
