CREATE
DATABASE IF NOT EXISTS craftcove2;
USE
craftcove2;

CREATE TABLE tag
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE category
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE product
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)   NOT NULL UNIQUE,
    img_url     VARCHAR(255),
    description TEXT,
    price       DECIMAL(10, 2) NOT NULL,
    total_sold  INT       DEFAULT 0,
    category_id INT            NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    stock       INT(11) DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
);

CREATE TABLE tag_product
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    tag_id     INT,
    product_id INT,
    FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE,
    UNIQUE KEY (tag_id, product_id) -- Ensure uniqueness of tag-product pairs
);

CREATE TABLE user_ac
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    profile_img VARCHAR(255) NULL,
    address     VARCHAR(255) NOT NULL,
    phone       VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    password    VARCHAR(255) NOT NULL
);



CREATE TABLE cart
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    product_id  INT            NOT NULL,
    user_id     INT            NOT NULL,
    quantity    INT            NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status      ENUM('pending', 'process', 'complete') DEFAULT 'pending',
    coupon_used BOOLEAN DEFAULT False,
    FOREIGN KEY (user_id) REFERENCES user_ac (id) ON DELETE CASCADE
);



CREATE TABLE review
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    product_id  INT,
    user_id INT,
    comment     TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user_ac (id) ON DELETE CASCADE
);



CREATE table coupon_code
(
    id       INT PRIMARY KEY AUTO_INCREMENT,
    name     VARCHAR(255) NOT NULL  UNIQUE,
    discount INT DEFAULT 5
);


CREATE TABLE applied_coupon
(
    id        INT PRIMARY KEY AUTO_INCREMENT,
    user_id   INT NOT NULL,
    coupon_id INT NOT NULL,
    applied   BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES user_ac (id),
    FOREIGN KEY (coupon_id) REFERENCES coupon_code (id)
);
