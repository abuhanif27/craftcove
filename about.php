<?php require 'helpers.php'; ?>
<?php loadPartial('header'); ?>

<section class="py-2">
    <style>
        .testimonial-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .testimonial {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .testimonial p {
            margin-bottom: 10px;
            color: #555;
        }

        .testimonial p:last-child {
            margin-bottom: 0;
        }

        .testimonial p.font-bold {
            font-weight: bold;
        }

        .craft-info {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #f3f4f6;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .craft-info ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .craft-info ul li {
            margin-bottom: 10px;
            color: #555;
        }

        .craft-info #button {
            background-color: #f66c02;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .craft-info #button:hover {
            background-color: #d84c02;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .social-icons a {
            margin: 0 10px;
            color: #555;
            transition: color 0.3s ease;
            margin: 1rem 0;
        }

        .social-icons a:hover {
            color: #f2716c;
        }
    </style>
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-3xl font-bold tracking-wide mb-2 text-center uppercase text-slate-800">Discover the Artistry of Craftcove</h1>
        <p class="text-md leading-relaxed text-center text-slate-600 mb-2">Where heritage meets craftsmanship</p>
        <div class="bg-slate-400 pt-1 mb-4"></div>
        <div class="testimonial-container">
            <div class="testimonial">
                <p class="text-md mb-4 tracking-wider">"Craftcove is a gem! Their dedication to preserving traditional craftsmanship is
                    truly inspiring. I've purchased several items from their platform, and each piece tells a unique
                    story."</p>
                <p class="text-md font-bold">- Motin Bhuyian, satisfied customer</p>
            </div>
            <div class="testimonial">
                <p class="text-md mb-4 tracking-wider">"I stumbled upon Craftcove while searching for unique gifts, and I'm so glad I
                    did! Their collection is exquisite, and the stories behind each craft add so much value to the
                    products."</p>
                <p class="text-md font-bold">- Baten Miah, happy customer</p>
            </div>
            <div class="testimonial">
                <p class="text-md mb-4 tracking-wider">"Craftcove exceeded my expectations! The quality of their products is
                    exceptional, and their customer service is top-notch. I'll definitely be a returning customer!"</p>
                <p class="text-md font-bold">- Jorina Akter, delighted customer</p>
            </div>
            <!-- Add more testimonials as needed -->
        </div>
        <div class="craft-info">
            <p class="text-lg leading-relaxed mb-4">Craftcove is a platform dedicated to preserving and promoting
                Bangladeshi heritage crafts. Our mission is to provide a sustainable livelihood for artisans while
                sharing their exceptional handmade craftworks with the world.</p>
            <p class="text-lg leading-relaxed mb-4">We believe every craft tells a story, and every artisan has a dream.
                That's why we're committed to:</p>
            <ul>
                <li class="text-lg"><i class="fa-solid fa-feather-pointed"></i> &nbsp; Empowering artisans to showcase their skills and creativity</li>
                <li class="text-lg"><i class="fa-solid fa-feather-pointed"></i> &nbsp; Preserving traditional craftsmanship and cultural heritage</li>
                <li class="text-lg"><i class="fa-solid fa-feather-pointed"></i> &nbsp; Providing a platform for artisans to connect with customers worldwide</li>
                <li class="text-lg"><i class="fa-solid fa-feather-pointed"></i> &nbsp; Ensuring fair trade practices and sustainable livelihoods</li>
            </ul>
            <a id="button" class="mt-8 block mx-auto" href="index.php">Explore Our Crafts</a>
        </div>
        <h2 class="text-2xl font-bold uppercase tracking-wide mt-12 mb-4 text-slate-800 underline underline-offset-8">Our Story</h2>
        <p class="text-lg leading-relaxed mb-8">Craftcove was founded by a team of passionate individuals who believe in
            the power of craftsmanship and cultural heritage. With years of experience in the industry, we've built a
            community of artisans, designers, and customers who share our vision.</p>
        <img src="team.jpeg" alt="Craftcove Team" class="w-full md:w-1/2 mb-8 rounded-lg shadow-lg mx-auto">
        <h2 class="text-2xl tracking-wide font-bold mt-12 mb-4 uppercase text-slate-800 underline underline-offset-8">Join Our Community</h2>
        <p class="text-lg leading-relaxed mb-4">Be part of our journey to preserve cultural heritage and empower
            artisans. Follow us on social media to stay updated on new arrivals, promotions, and stories from our
            artisans.</p>
        <div class="bg-slate-400 pt-1"></div>
        <div class="social-icons flex space-x-8">
            <a href="https://web.facebook.com/hn.hanif.2m19k7" class="text-blue-500 hover:text-blue-700">
                <i class="fab fa-facebook-f fa-2x"></i>
            </a>
            <a href="https://twitter.com/AlienSoldier4" class="text-blue-500 hover:text-blue-700">
                <i class="fab fa-twitter fa-2x"></i>
            </a>
            <a href="https://www.instagram.com/hn.hanif.2m19k7/" class="text-blue-500 hover:text-blue-700">
                <i class="fab fa-instagram fa-2x"></i>
            </a>
        </div>
    </div>
</section>

<?php loadPartial('footer'); ?>
