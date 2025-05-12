CREATE DATABASE IF NOT EXISTS CPE241_SHOP;
USE CPE241_SHOP;

CREATE USER IF NOT EXISTS 'sysadmin'@'%' IDENTIFIED BY '123';
GRANT ALL PRIVILEGES ON CPE241_SHOP.* TO 'sysadmin'@'%';


CREATE TABLE `tbl_users` (
    `user_ID` int(11) NOT NULL AUTO_INCREMENT,
    `firstName` varchar(50) DEFAULT NULL,
    `midName` varchar(50) DEFAULT NULL,
    `lastName` varchar(50) DEFAULT NULL,
    `userName` varchar(50) DEFAULT NULL,
    `password_hash` varchar(255) DEFAULT NULL,
    `role` varchar(20) DEFAULT 'user',
    PRIMARY KEY (`user_ID`),
    UNIQUE KEY `userName` (`userName`)
);

CREATE TABLE `tbl_user_phones` (
    `user_ID` int(11) NOT NULL,
    `phone_number` varchar(20) NOT NULL,
    `is_primary` tinyint(1) DEFAULT 0,
    PRIMARY KEY (`user_ID`, `phone_number`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`)
);

CREATE TABLE `tbl_address` (
    `user_ID` int(11) NOT NULL,
    `buildingNumber` varchar(20) NOT NULL,
    `district` varchar(100) DEFAULT NULL,
    `province` varchar(100) DEFAULT NULL,
    `subdistrict` varchar(100) DEFAULT NULL,
    `country` varchar(100) DEFAULT NULL,
    `zip_code` varchar(10) DEFAULT NULL,
    `is_primary` tinyint(1) DEFAULT 0,
    `type` varchar(10) DEFAULT 'house',
    `txt` text DEFAULT NULL,
    PRIMARY KEY (`user_ID`, `buildingNumber`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`)
);

CREATE TABLE `tbl_shops` (
    `shop_ID` int(11) NOT NULL AUTO_INCREMENT,
    `shopName` varchar(100) DEFAULT NULL,
    `user_ID` int(11) DEFAULT NULL,
    PRIMARY KEY (`shop_ID`),
    KEY `user_ID` (`user_ID`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`)
);

CREATE TABLE `tbl_categories` (
    `cate_ID` int(11) NOT NULL AUTO_INCREMENT,
    `cateName` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`cate_ID`)
);

CREATE TABLE `tbl_products` (
    `product_ID` int(11) NOT NULL AUTO_INCREMENT,
    `shop_ID` int(11) DEFAULT NULL,
    `cate_ID` int(11) DEFAULT NULL,
    `productName` varchar(100) DEFAULT NULL,
    `imgPath` varchar(255) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    `quantity` int(11) DEFAULT NULL,
    `is_delete` int(2) DEFAULT 0,
    CHECK (price > 0),
    CHECK (quantity > 0),
    PRIMARY KEY (`product_ID`),
    KEY `shop_ID` (`shop_ID`),
    KEY `cate_ID` (`cate_ID`),
    FOREIGN KEY (`shop_ID`) REFERENCES `tbl_shops` (`shop_ID`),
    FOREIGN KEY (`cate_ID`) REFERENCES `tbl_categories` (`cate_ID`)
);

CREATE TABLE `tbl_product_stats` (
    `product_ID` int(11) NOT NULL,
    `addToCart` int(11) DEFAULT 0,
    `visit` int(11) DEFAULT 0,
    `numSold` int(11) DEFAULT 0,
    `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`product_ID`),
    FOREIGN KEY (`product_ID`) REFERENCES `tbl_products` (`product_ID`)
);

CREATE TABLE `tbl_carts` (
    `user_ID` int(11) NOT NULL,
    `product_ID` int(11) NOT NULL,
    `quantity` int(11) DEFAULT NULL,
    `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    ,CHECK (quantity > 0),
    PRIMARY KEY (`user_ID`, `product_ID`),
    KEY `product_ID` (`product_ID`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`),
    FOREIGN KEY (`product_ID`) REFERENCES `tbl_products` (`product_ID`)
);

CREATE TABLE `tbl_coupons` (
    `coupon_ID` int(11) NOT NULL AUTO_INCREMENT,
    `couponCode` varchar(50) DEFAULT NULL,
    `discount` decimal(5,2) DEFAULT NULL,
    `minOrderValue` decimal(10,2) DEFAULT NULL,
    `expDate` date DEFAULT NULL,
    `remain` int(11) DEFAULT NULL,
    `is_delete` int(2) DEFAULT 0,
    CHECK (discount <= 100),
    CHECK (minOrderValue > 0),
    CHECK (remain > 0)
    PRIMARY KEY (`coupon_ID`)
);

CREATE TABLE `tbl_transactions` (
    `trans_ID` int(11) NOT NULL AUTO_INCREMENT,
    `user_ID` int(11) DEFAULT NULL,
    `sumPrice` decimal(10,2) DEFAULT NULL,
    `coupon_ID` int(11) DEFAULT NULL,
    `grandTotal` decimal(10,2) DEFAULT NULL,
    `paid` tinyint(1) DEFAULT 0,
    `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `transport_state` varchar(50) DEFAULT 'Pending',
    PRIMARY KEY (`trans_ID`),
    KEY `user_ID` (`user_ID`),
    KEY `coupon_ID` (`coupon_ID`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`),
    FOREIGN KEY (`coupon_ID`) REFERENCES `tbl_coupons` (`coupon_ID`)
);

CREATE TABLE `tbl_transaction_items` (
    `trans_ID` int(11) NOT NULL,
    `product_ID` int(11) NOT NULL,
    `quantity` int(11) DEFAULT NULL,
    `price` decimal(10,2) DEFAULT NULL,
    CHECK (quantity > 0),
    PRIMARY KEY (`trans_ID`, `product_ID`),
    KEY `product_ID` (`product_ID`),
    FOREIGN KEY (`trans_ID`) REFERENCES `tbl_transactions` (`trans_ID`),
    FOREIGN KEY (`product_ID`) REFERENCES `tbl_products` (`product_ID`)
);

CREATE TABLE `tbl_reviews` (
    `review_ID` int(11) NOT NULL AUTO_INCREMENT,
    `product_ID` int(11) NOT NULL,
    `user_ID` int(11) NOT NULL,
    `starRate` TINYINT UNSIGNED DEFAULT NULL,
    `txt` text DEFAULT NULL,
    `date_posted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    CHECK (starRate > 0 AND starRate <= 5),
    PRIMARY KEY (`review_ID`),
    KEY `product_ID` (`product_ID`),
    KEY `user_ID` (`user_ID`),
    FOREIGN KEY (`product_ID`) REFERENCES `tbl_products` (`product_ID`),
    FOREIGN KEY (`user_ID`) REFERENCES `tbl_users` (`user_ID`)
);

CREATE TABLE `tbl_review_images` (
    `review_ID` int(11) NOT NULL, 
    `image_number` int(11) NOT NULL,
    `imgPath` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`review_ID`, `image_number`),
    FOREIGN KEY (`review_ID`) REFERENCES `tbl_reviews` (`review_ID`)
);