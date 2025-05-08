INSERT INTO tbl_users (user_ID, firstName, midName, lastName, userName, password_hash, `role`) VALUES
(1, 'John', 'A.', 'Doe', 'admin', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'admin'),
(2, 'Jane', 'B.', 'Smith', 'janesmith', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'seller'),
(3, 'Mike', 'C.', 'Johnson', 'mikej', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'user'),
(4, 'Alice', 'D.', 'Williams', 'alicew', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'user'),
(5, 'Robert', 'E.', 'Brown', 'robbie', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'admin'),
(6, 'David', 'F.', 'Miller', 'davemiller', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'user'),
(7, 'Emily', 'G.', 'Davis', 'emilydavis', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'user'),
(8, 'Daniel', 'H.', 'Garcia', 'danielg', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'admin'),
(9, 'Sophia', 'I.', 'Martinez', 'sophiam', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'user'),
(10, 'James', 'J.', 'Rodriguez', 'jamesr', '$2a$10$qy9leoJloExThApTbN0ZdOANvMR1BIwUBB/X29UXRVEFFVMC5aes6', 'admin');

INSERT INTO tbl_user_phones (user_ID, phone_number, is_primary) VALUES
(1, '0812345678', 1),
(1, '0898765432', 0),
(2, '0823456789', 1),
(3, '0834567890', 1),
(4, '0845678901', 1),
(6, '0856781234', 1),
(7, '0867892345', 1),
(8, '0878903456', 1),
(9, '0889014567', 1),
(10, '0890125678', 1);


INSERT INTO tbl_address 
(user_ID, buildingNumber, district, province, subdistrict, country, zip_code, is_primary, `type`, txt) 
VALUES
(1, '123/45', 'Bang Kapi', 'Bangkok', 'Hua Mak', 'Thailand', '10240', 1, 'house', 'Near Mall'),
(3, '678/90', 'Lat Krabang', 'Bangkok', 'Lat Krabang', 'Thailand', '10520', 0, 'office', NULL),
(3, '55/66', 'Huai Khwang', 'Bangkok', 'Samsen Nok', 'Thailand', '10310', 0, 'house', NULL),
(3, '77/88', 'Din Daeng', 'Bangkok', 'Din Daeng', 'Thailand', '10400', 1, 'office', 'office A'),
(3, '99/11', 'Chatuchak', 'Bangkok', 'Chomphon', 'Thailand', '10900', 0, 'house', NULL),
(6, '111/22', 'Suan Luang', 'Bangkok', 'Suan Luang', 'Thailand', '10250', 0, 'office', 'Near Shopping Mall'),
(3, '333/44', 'Bang Na', 'Bangkok', 'Bang Na Nuea', 'Thailand', '10260', 1, 'house', 'Townhouse'),
(8, '444/55', 'Phaya Thai', 'Bangkok', 'Samsen Nai', 'Thailand', '10300', 0, 'office', 'Apartment B1'),
(9, '555/66', 'Chatuchak', 'Bangkok', 'Lat Yao', 'Thailand', '10900', 1, 'house', 'House 10'),
(10, '666/77', 'Lat Phrao', 'Bangkok', 'Lat Phrao', 'Thailand', '10320', 1, 'house', 'Villa 12');



INSERT INTO `tbl_shops` (`shopName`, `user_ID`) VALUES 
('Buddhist Shop', 1),
('Christian Store', 2),
('Islamic Goods', 3),
('Godly Creations', 4),
('Other Faiths Shop', 5);

INSERT INTO tbl_categories (cateName) VALUES
(1, 'buddhist'),
(2, 'christian'),
(3, 'islamic'),
(4, 'god'),
(5, 'others');

INSERT INTO `tbl_products` (`shop_ID`, `cate_ID`, `productName`, `imgPath`, `description`, `price`, `quantity`) VALUES
(1, 1, 'Buddha Statue', '/images/buddha_statue.jpg', 'A serene Buddha statue for meditation and decoration.', 100.00, 50),
(2, 2, 'Christian Cross Necklace', '/images/christian_cross.jpg', 'A silver cross necklace, perfect for daily wear.', 25.00, 100),
(3, 3, 'Islamic Prayer Mat', '/images/prayer_mat.jpg', 'High-quality prayer mat for daily use in prayers.', 30.00, 75),
(4, 4, 'God’s Blessing Bracelet', '/images/god_blessing_bracelet.jpg', 'A symbolic bracelet representing divine blessings.', 15.00, 120),
(5, 5, 'Various Religious Symbols', '/images/religious_symbols.jpg', 'A collection of items representing various faiths.', 20.00, 200),
(1, 1, 'Buddhist Incense Set', '/images/buddhist_incense.jpg', 'Fragrant incense sticks used in Buddhist rituals and meditation.', 12.00, 100),
(2, 2, 'Holy Bible (Leather Cover)', '/images/holy_bible.jpg', 'Premium edition of the Holy Bible with a leather cover.', 35.00, 75),
(3, 3, 'Quran Stand', '/images/quran_stand.jpg', 'Wooden stand for reading the Quran comfortably.', 20.00, 60),
(4, 4, 'Prayer Candle Set', '/images/prayer_candles.jpg', 'Set of 6 candles for spiritual practices and prayer.', 18.00, 90),
(5, 5, 'Faith-Themed Wall Art', '/images/faith_wall_art.jpg', 'Inspirational wall art with quotes from various faiths.', 22.00, 110);


INSERT INTO tbl_product_stats (product_ID, addToCart, visit, numSold) VALUES
(1, 25, 200, 15),
(2, 30, 150, 10),
(3, 15, 100, 5),
(4, 40, 250, 20),
(5, 10, 80, 3),
(6, 12, 100, 8),
(7, 25, 180, 12),
(8, 10, 90, 6),
(9, 18, 150, 9),
(10, 14, 120, 7);


INSERT INTO tbl_carts (user_ID, product_ID, quantity) VALUES
(1, 2, 1),
(2, 1, 2),
(3, 3, 1),
(4, 5, 4),
(5, 4, 2),
(6, 1, 3),
(7, 2, 2),
(8, 3, 1),
(9, 4, 2),
(10, 5, 5),
(1, 6, 2),
(2, 7, 1),
(3, 8, 1),
(4, 9, 3),
(5, 10, 2);


INSERT INTO tbl_coupons (couponCode, discount, minOrderValue, expDate, remain) VALUES
('PRAYFORBUDDHA', 10.00, 500.00, '2025-12-31', 100),
('EMERALD', 5.00, 300.00, '2025-08-31', 50),
('WELCOME15', 15.00, 800.00, '2025-11-30', 200),
('99SATHU', 20.00, 1500.00, '2025-11-29', 20),
('SIDDHARTHA', 5.00, 200.00, '2026-01-01', 500);

INSERT INTO tbl_transactions (user_ID, sumPrice, coupon_ID, grandTotal, paid, transport_state) VALUES
(1, 2598.00, 1, 2338.20, 1, 'Shipped'),
(2, 799.00, 2, 759.05, 1, 'Processing'),
(3, 450.00, NULL, 450.00, 0, 'Pending'),
(4, 1196.00, 3, 1016.60, 1, 'Delivered'),
(5, 898.00, 1, 808.20, 1, 'Cancelled'),
(6, 300.00, NULL, 300.00, 1, 'Shipped'),
(7, 1000.00, 5, 950.00, 1, 'Processing'),
(8, 450.00, NULL, 450.00, 0, 'Pending'),
(9, 1200.00, 4, 1080.00, 1, 'Delivered'),
(10, 1000.00, 2, 950.00, 1, 'Cancelled');

INSERT INTO tbl_transaction_items (trans_ID, product_ID, quantity, price) VALUES
(1, 1, 2, 1299.00),
(2, 2, 1, 799.00),
(3, 3, 1, 450.00),
(4, 5, 4, 299.00),
(5, 4, 2, 299.00),
(6, 1, 3, 100.00),
(7, 2, 2, 25.00),
(8, 3, 1, 30.00),
(9, 4, 2, 15.00),
(10, 5, 5, 20.00);

INSERT INTO `tbl_reviews` (`product_ID`, `user_ID`, `starRate`, `txt`, `date_posted`) VALUES
(1, 1, 4.5, 'สวยตรงปก วัสดุไม่ก๊องแก๊ง เป็นที่ยึดเหนี่ยวจิตใจได้ดีค่ะ', '2025-05-08 10:00:00'),
(2, 2, 4, 'สร้อยสวย ไม่ลอกง่าย เข้ากับทุกช่วงวัยฮะ', '2025-05-08 10:10:00'),
(3, 3, 2.5, 'Bismillah เสื่อสำหรับละหมาด วัสดุคุณภาพชั้นเยี่ยมในราคาเข้าถึงได้ เหมาะสำหรับ everyday use', '2025-05-08 10:15:00'),
(4, 4, 4, 'สวย👍', '2025-05-08 10:20:00'),
(5, 5, 3, 'ดีคับ แต่ขอผ่านก่อน', '2025-05-08 10:30:00'),
(1, 6, 5, 'สุดยอด รักษ์โลกแถมยังได้ช่วยเหลือผู้พิการด้วย', '2025-05-08 11:00:00'),
(2, 7, 4, '👍👍👍', '2025-05-08 11:10:00'),
(3, 8, 5, 'Perfect for daily use.', '2025-05-08 11:20:00'),
(4, 9, 4, 'สีสวย เนื่้อผ้าเย็น เหมาะสำหรับเด็ก', '2025-05-08 11:30:00'),
(5, 10, 1, 'ร้านส่งช้ามาก รอนานจนสั่งร้านอื่นไปแล้ว', '2025-05-08 11:40:00'),
(6, 1, 4.5, 'รีวิวเอาคอยน์ครับ', '2025-05-08 12:00:00'),
(7, 2, 5, 'ร้านนี้สั่งซ้ำไปสองครั้งแล้ว สวยถูกใจจ้า', '2025-05-08 12:10:00'),
(8, 3, 4, 'ดี', '2025-05-08 12:20:00'),
(9, 4, 5, 'ใช้ดี รู้สึกถึงความมงคล🪷', '2025-05-08 12:30:00'),
(10, 5, 4, 'สวยจร้า', '2025-05-08 12:40:00');
(6, 6, 2, 'ของไม่ตรงปกเท่าไหร่ แอบผิดหวังนิดนึงค่ะ', '2025-05-08 12:50:00'),
(7, 7, 3.5, 'สินค้าโอเคในระดับหนึ่ง แต่แพ็คเกจดูธรรมดาไปนิด', '2025-05-08 13:00:00'),
(8, 8, 5, 'ของมาถึงไว คุณภาพเกินราคามาก ประทับใจสุดๆ', '2025-05-08 13:10:00'),
(9, 9, 2.5, 'พอใช้ได้ แต่กลิ่นแรงมาก ต้องซักก่อนถึงจะโอเค', '2025-05-08 13:20:00'),
(10, 10, 4, 'ดีค่ะ ส่งไวเกินคาด แพ็คมาดีไม่มีตำหนิเลย', '2025-05-08 13:30:00'),
(1, 11, 1.5, 'ผิดหวังเลยค่ะ วัสดุดูถูกมาก ไม่คุ้มกับราคา', '2025-05-08 13:40:00'),
(2, 12, 5, 'ให้เต็มสิบไม่หักเลยครับ สวยโดนใจมาก', '2025-05-08 13:50:00'),
(3, 13, 3, 'ใช้ได้ค่ะ ธรรมดา ไม่ว้าวแต่ก็ไม่แย่', '2025-05-08 14:00:00'),
(4, 14, 4.5, 'สีตรงปก เนื้อผ้านุ่มมาก ลูกชอบเลย', '2025-05-08 14:10:00'),
(5, 15, 2, 'ไม่ค่อยโอเคเท่าไหร่ แพ็คเกจเยินมาเลย', '2025-05-08 14:20:00');
(6, 16, 3.5, 'ลายสวยนะคะ แต่ขนาดเล็กกว่าที่คิดนิดหน่อย', '2025-05-08 14:30:00'),
(7, 17, 1, 'ผิดหวังมากค่ะ เหมือนของมือสองเลย', '2025-05-08 14:40:00'),
(8, 18, 4.5, 'ของดีเกินคาด ใช้งานทุกวันไม่มีปัญหาเลยค่ะ', '2025-05-08 14:50:00'),
(9, 19, 3, 'สินค้าพอใช้ได้ แต่ขนส่งช้ามาก', '2025-05-08 15:00:00'),
(10, 20, 5, 'สวยมากกกกกกกกกกกกกกกกกกกกก', '2025-05-08 15:10:00'),
(1, 21, 2, 'วัสดุไม่ดีเลยค่ะ น่าจะมีบอกให้ชัดเจนกว่านี้', '2025-05-08 15:20:00'),
(2, 22, 4, 'ใส่แล้วดูดีมากฮะ คนชมหลายคนเลย', '2025-05-08 15:30:00'),
(3, 23, 3.5, 'ดีแต่แพงไปหน่อย ถ้าถูกกว่านี้จะให้ 5 ดาว', '2025-05-08 15:40:00'),
(4, 24, 5, 'ซื้อมาฝากแม่ แม่ชอบมากเลยค่ะ', '2025-05-08 15:50:00'),
(5, 25, 2.5, 'ของจริงกับรูปต่างกันเยอะเลย เสียใจเบาๆ', '2025-05-08 16:00:00');


INSERT INTO tbl_review_images (review_ID, image_number, imgPath) VALUES
(1, 1, '/images/review_buddha_statue_1.jpg'),
(1, 2, '/images/review_buddha_statue_2.jpg'),
(2, 1, '/images/review_christian_cross_1.jpg'),
(3, 1, '/images/review_prayer_mat_1.jpg'),
(4, 1, '/images/review_god_blessing_bracelet_1.jpg'),
(5, 1, '/images/review_religious_symbols_1.jpg'),
(6, 1, '/images/review_buddha_statue_3.jpg'),
(7, 1, '/images/review_christian_cross_2.jpg'),
(8, 1, '/images/review_prayer_mat_2.jpg'),
(9, 1, '/images/review_god_blessing_bracelet_2.jpg'),
(10, 1, '/images/review_religious_symbols_2.jpg'),
(11, 1, '/images/review_incense_1.jpg'),
(12, 1, '/images/review_bible_1.jpg'),
(13, 1, '/images/review_quran_stand_1.jpg'),
(14, 1, '/images/review_prayer_candles_1.jpg'),
(15, 1, '/images/review_wall_art_1.jpg');

