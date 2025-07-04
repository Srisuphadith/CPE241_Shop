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
('วัดท่าไม่กิน..ถือว่าผิด', 1),
('Jesus tell us that shop', 2),
('Islamic Goods', 3),
('Little Gods Studio', 4),
('เหลืออะไรโยนมาทางร้านนี้', 5);

INSERT INTO `tbl_categories` (`cate_ID,cateName`) VALUES
(1, 'buddhist'),
(2, 'christian'),
(3, 'islamic'),
(4, 'god'),
(5, 'others');

INSERT INTO `tbl_products` (`product_ID`, `shop_ID`, `cate_ID`, `productName`, `imgPath`, `description`, `price`, `quantity`) VALUES
(1, 1, 1, 'พระสติ มวลสารถุงฟอยล์สีเขียว', 'img/product/buddhist/watthamaikin/sati1.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(2, 1, 1, 'พระสติ มวลสารถุงฟอยล์สีดำ', 'img/product/buddhist/watthamaikin/sati2.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 20),
(3, 1, 1, 'พระสติ มวลสาร Recycled PET', 'img/product/buddhist/watthamaikin/sati3.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(4, 1, 1, 'พระสติ มวลสารพลาสติกผสมกากกาแฟ', 'img/product/buddhist/watthamaikin/sati4.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(5, 1, 1, 'พระสติ มวลสาร Green ABS ผสมผงไม้', 'img/product/buddhist/watthamaikin/sati5.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(6, 1, 1, 'พระสติ มวลสารแก้วกาแฟพลาสติก', 'img/product/buddhist/watthamaikin/sati6.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(7, 1, 1, 'พระสติ มวลสารกระบอกไส้กรองน้ำ', 'img/product/buddhist/watthamaikin/sati7.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(8, 1, 1, 'พระสติ มวลสารกล่อง UHT', 'img/product/buddhist/watthamaikin/sati8.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(9, 1, 1, 'พระสติ มวลสารเศษแหอวน', 'img/product/buddhist/watthamaikin/sati9.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(10, 1, 1, 'พระสติ มวลสารถังน้ำพลาสติก', 'img/product/buddhist/watthamaikin/sati10.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(11, 1, 1, 'พระสติ มวลสารเศษพลาสติกยืด', 'img/product/buddhist/watthamaikin/sati11.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(12, 1, 1, 'พระสติ มวลสารกล่องข้าวพลาสติก', 'img/product/buddhist/watthamaikin/sati12.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(13, 1, 1, 'พระสติ มวลสารพลาสติกผสมซีเมนต์', 'img/product/buddhist/watthamaikin/sati13.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(14, 1, 1, 'พระสติ มวลสารถ้วยโยเกิร์ต', 'img/product/buddhist/watthamaikin/sati14.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(15, 1, 1, 'พระสติ มวลสารแผ่น CD/DVD', 'img/product/buddhist/watthamaikin/sati15.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(16, 1, 1, 'พระสติ มวลสารฝาขวดน้ำ', 'img/product/buddhist/watthamaikin/sati16.png', 'พระสติ มีให้เลือกบูชา 16 มวลสาร มีกรรมวิธีปลุกเสกจากการอัดแน่นด้วยวัสดุรีไซเคิลมาย่อย บด ขึ้นรูปใหม่ จดปราณีต หมดจด งดงาม นี่คือพระที่ขึ้นชื่อเรื่องอยู่ยงคงกระพันนานมากกว่า 100 ปี', 200.00, 50),
(17, 4, 4, 'พระพิฆเนศ Vitruvian man', 'img/product/god/littlegod/ganeshavitruvian.png', 'พระพิฆเนศ Vitruvian man จากชุด Masterpiece of the world วัสดุโลหะบรอนซ์ ความสูง 5 ซม.', 590.00, 10),
(18, 4, 4, 'กล่องสุ่ม Chibi Ganesha รุ่น Material Series', 'img/product/god/littlegod/chibiganesha.png', 'รวบรวมศิลปินอาร์ตทอยไทย 6 แบรนด์ มาร่วมสร้างสรรค์ 6 ผลงานที่มีแรงบันดาลใจจากประติมากรรมพระพิฆเณศศิลปะต่างๆ ในประวัติศาสตร์ไทย วัสดุซอฟต์ไวนิล ความสูง 5 ซม.', 220.00, 100), 
(19, 3, 3, 'พรมละหมาด สีม่วง', 'img/product/islam/islamgoods/prayermat1.png', 'ผ้าปูละหมาดชนิดบาง สำหรับพกพา ผืนใหญ่ ขนาด 109x69 ซม. เหมาะสำหรับทุกวัย เหมาะสำหรับให้เป็นของขวัญ งานแต่งงาน งานขึ้นบ้านใหม่ แนะนำให้ซักด้วยมือเพื่อยืดอายุการใช้งาน', 190.00, 1000), 
(20, 3, 3, 'พรมละหมาด สีเขียว', 'img/product/islam/islamgoods/prayermat2.png', 'ผ้าปูละหมาดชนิดบาง สำหรับพกพา ผืนใหญ่ ขนาด 109x69 ซม. เหมาะสำหรับทุกวัย เหมาะสำหรับให้เป็นของขวัญ งานแต่งงาน งานขึ้นบ้านใหม่ แนะนำให้ซักด้วยมือเพื่อยืดอายุการใช้งาน', 190.00, 1000), 
(21, 2, 2, 'สร้อยเงิน จี้ไม้กางเขน', 'img/product/christ/jesusshop/necklece.png', 'สร้อยพร้อมจี้ไม้กางเขน เงินแท้ 92.5% น้ำหนัก 2.35 กรัม เหมาะเป็นของขวัญสำหรับคนพิเศษ', 5490.00, 1000),
(22, 5, 5, 'อาร์ตทอย Golden boy', 'img/product/other/others/goldenboy.png', 'วัสดุเรซิ่น ความสูงประมาณ ๕ ซม. ตั้งโชว์ก็ได้ บูชาก็ดี เป็นสิริมงคล', 199.00, 1000), 
(23, 5, 5, 'อาร์ตทอยนางไหว้', 'img/product/other/others/nangwai.png', 'วัสดุเรซิ่น ความสูงประมาณ ๕ ซม. ตั้งโชว์ก็ได้ บูชาก็ดี เป็นสิริมงคล', 199.00, 1000), 
(24, 5, 5, 'อาร์ตทอยพระเจ้าสุริยวรมันที่ ๑', 'img/product/other/others/suriya1.png', 'วัสดุเรซิ่น ความสูงประมาณ ๕ ซม. ตั้งโชว์ก็ได้ บูชาก็ดี เป็นสิริมงคล', 199.00, 1000), 
(25, 5, 5, 'กาชาปองแรกนาขวัญ 1 กล่อง', 'img/product/other/others/reakna.png', 'กล่องสุ่มแรกนาขวัญ มีให้สะสมทั้งหมด 6 แบบ ได้แก่ ชุดพระโคเสี่ยงทาย พระยาแรกนา พระโคแรกนาขวัญ พระโคสำรอง เจ้าพนักงาน เจ้าพนักงานถือถาด และเทพีหาบทอง-หาบเงิน', 99.00, 1000), 
(26, 5, 5, 'กาชาปองตุ๊กตาดินเผาแหลมทองคอลเลกชันใหม่ 1 กล่อง', 'img/product/other/others/dinphao.png', 'กล่องสุ่มตุ๊กตาดินเผาแหลมทองคอลเลกชันใหม่ มีให่ช้สะสมทั้งหมด 6 แบบ ได้แก่ หินดุ เด็กชายนั่งคุกเข่า และนรสิงห์ ทั้งหมดทำจากวัสดุดินเผาของจริง', 199.00, 21);

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

INSERT INTO `tbl_transaction_items` (`trans_ID`, `product_ID`, `quantity`, `price`) VALUES
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
(7, 1, 4.5, 'สวยตรงปก วัสดุไม่ก๊องแก๊ง เป็นที่ยึดเหนี่ยวจิตใจได้ดีค่ะ', '2025-05-08 10:00:00'),
(8, 2, 4, 'สร้อยสวย ไม่ลอกง่าย เข้ากับทุกช่วงวัยฮะ', '2025-05-08 10:10:00'),
(9, 3, 2.5, 'Bismillah เสื่อสำหรับละหมาด วัสดุคุณภาพชั้นเยี่ยมในราคาเข้าถึงได้ เหมาะสำหรับ everyday use', '2025-05-08 10:15:00'),
(10, 4, 4, 'สวย👍', '2025-05-08 10:20:00'),
(11, 5, 3, 'ดีคับ แต่ขอผ่านก่อน', '2025-05-08 10:30:00'),
(12, 6, 5, 'สุดยอด รักษ์โลกแถมยังได้ช่วยเหลือผู้พิการด้วย', '2025-05-08 11:00:00'),
(13, 7, 4, '👍👍👍', '2025-05-08 11:10:00'),
(14, 8, 5, 'Perfect for daily use.', '2025-05-08 11:20:00'),
(15, 9, 4, 'สีสวย เนื่้อผ้าเย็น เหมาะสำหรับเด็ก', '2025-05-08 11:30:00'),
(16, 10, 1, 'ร้านส่งช้ามาก รอนานจนสั่งร้านอื่นไปแล้ว', '2025-05-08 11:40:00'),
(17, 1, 4.5, 'รีวิวเอาคอยน์ครับ', '2025-05-08 12:00:00'),
(18, 2, 5, 'ร้านนี้สั่งซ้ำไปสองครั้งแล้ว สวยถูกใจจ้า', '2025-05-08 12:10:00'),
(19, 3, 4, 'ดี', '2025-05-08 12:20:00'),
(20, 4, 5, 'ใช้ดี รู้สึกถึงความมงคล🪷', '2025-05-08 12:30:00'),
(21, 5, 4, 'สวยจร้า', '2025-05-08 12:40:00'),
(22, 6, 2, 'ของไม่ตรงปกเท่าไหร่ แอบผิดหวังนิดนึงค่ะ', '2025-05-08 12:50:00'),
(23, 7, 3.5, 'สินค้าโอเคในระดับหนึ่ง แต่แพ็คเกจดูธรรมดาไปนิด', '2025-05-08 13:00:00'),
(24, 8, 5, 'ของมาถึงไว คุณภาพเกินราคามาก ประทับใจสุดๆ', '2025-05-08 13:10:00'),
(25, 9, 2.5, 'พอใช้ได้ แต่กลิ่นแรงมาก ต้องซักก่อนถึงจะโอเค', '2025-05-08 13:20:00'),
(26, 10, 4, 'ดีค่ะ ส่งไวเกินคาด แพ็คมาดีไม่มีตำหนิเลย', '2025-05-08 13:30:00'),
(1, 11, 1.5, 'ผิดหวังเลยค่ะ วัสดุดูถูกมาก ไม่คุ้มกับราคา', '2025-05-08 13:40:00'),
(2, 12, 5, 'ให้เต็มสิบไม่หักเลยครับ สวยโดนใจมาก', '2025-05-08 13:50:00'),
(3, 13, 3, 'ใช้ได้ค่ะ ธรรมดา ไม่ว้าวแต่ก็ไม่แย่', '2025-05-08 14:00:00'),
(4, 14, 4.5, 'สีตรงปก เนื้อผ้านุ่มมาก ลูกชอบเลย', '2025-05-08 14:10:00'),
(5, 15, 2, 'ไม่ค่อยโอเคเท่าไหร่ แพ็คเกจเยินมาเลย', '2025-05-08 14:20:00'),
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
(10, 1, '/images/review_religious_symbols_2.jpg');
-- (11, 1, '/images/review_incense_1.jpg'),
-- (12, 1, '/images/review_bible_1.jpg'),
-- (13, 1, '/images/review_quran_stand_1.jpg'),
-- (14, 1, '/images/review_prayer_candles_1.jpg'),
-- (15, 1, '/images/review_wall_art_1.jpg');

