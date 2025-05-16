INSERT INTO tag(name)
VALUES ('Handcrafted'),
       ('Traditional'),
       ('Artisanal'),
       ('Heritage'),
       ('Folk'),
       ('Ethnic'),
       ('Eco-friendly'),
       ('Sustainable'),
       ('Natural'),
       ('Unique');



INSERT INTO category (name)
VALUES ('Home Decor'),
       ('Jewelry & Accessories'),
       ('Textiles'),
       ('Art & Sculpture'),
       ('Kitchenware'),
       ('Handloom'),
       ('Pottery & Ceramics'),
       ('Leather Goods'),
       ('Wooden Crafts'),
       ('Clothing & Apparel');



INSERT INTO product(stock,img_url,name, description, price, total_sold, category_id)
VALUES (50,'image/bamboo_wind_chimes.jpeg','Bamboo Wind Chimes',
        'Beautifully crafted bamboo wind chimes perfect for adding a serene touch to any outdoor space.', 14.99, 50, 1),
       (50,'image/beaded_necklace.jpeg','Beaded Necklace',
        'Elegant handcrafted necklace adorned with colorful beads, adding a touch of sophistication to any outfit.',
        24.99, 30, 2),
       (50,'image/woven_cotton_throw_blanket.jpeg','Woven Cotton Throw Blanket',
        'Soft and cozy cotton throw blanket handwoven with traditional patterns and vibrant colors.', 39.99, 25, 10),
       (50,'image/artisanal_ceramic_vase.jpeg','Artisanal Ceramic Vase',
        'Artisanal ceramic vase featuring unique patterns and textures, perfect for displaying flowers or as a standalone decor piece.',
        29.99, 40, 7),
       (50,'image/carved_wooden_sculpture.jpeg','Carved Wooden Sculpture',
        'Intricately hand-carved wooden sculpture showcasing traditional craftsmanship and cultural motifs.', 49.99, 20,
        4),
       (20,'image/stitched_leather_wallet.jpeg','Stitched Leather Wallet',
        'High-quality leather wallet meticulously hand-stitched for durability and style, featuring multiple compartments for organization.',
        59.99, 15, 8),
       (10,'image/painted_silk_scarf.jpeg','Painted Silk Scarf',
        'Luxurious hand-painted silk scarf featuring vibrant colors and exquisite detailing, adding a touch of elegance to any outfit.',
        39.99, 35, 10),
       (8,'image/clay_pottery_mug.jpeg','Clay Pottery Mug',
        'Artisanal clay pottery mug crafted with care, perfect for enjoying your favorite beverages in style.', 19.99,
        45, 7),
       (1,'image/crafted_wooden_picture_frame.jpeg','Crafted Wooden Picture Frame',
        'Beautifully handcrafted wooden picture frame with intricate details, perfect for displaying your cherished memories.',
        9.99, 55, 3),
       (0,'image/woven_basket_with_handles.jpeg','Woven Basket with Handles',
        'Sturdy handwoven basket with handles, perfect for storing blankets, toys, or as a decorative accent in your home.',
        69.99, 10, 6);



INSERT INTO user_ac(name,email,address,phone,password) VALUES ('Abu Hanif','admin@email.com','Araihazar, Narayanganj','+8801643983293','root1234');


INSERT INTO coupon_code(name)
VALUES ('ABU_HANIF'),
       ('MAY_2025'),
       ('FIRST_ORDER'),
       ('MD_ANIS');



INSERT INTO tag_product(tag_id, product_id)
VALUES (1, 1),
       (2, 1),
       (3, 1),
       (4, 2),
       (5, 2),
       (1, 2),
       (1, 3),
       (2, 3),
       (6, 3),
       (7, 4),
       (2, 4),
       (8, 4),
       (10, 5),
       (4, 5),
       (3, 5),
       (6, 6),
       (4, 6),
       (10, 6),
       (7, 7),
       (9, 7),
       (4, 7),
       (8, 8),
       (1, 8),
       (6, 8),
       (10, 9),
       (1, 9),
       (9, 9),
       (2, 10),
       (10, 10),
       (8, 10);
              