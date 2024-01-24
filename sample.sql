CREATE TABLE `ao_brand` (
  `brand_id` varchar(20) NOT NULL,
  `brand_name` varchar(50) DEFAULT NULL,
  `brand_image` varchar(255) DEFAULT NULL,
  `brand_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `cat_id` (`brand_id`)
);
INSERT INTO `ao_brand` VALUES ('B0001','Prime 1 studio','https://upload.wikimedia.org/wikipedia/commons/a/ab/Prime_1_Studio_Logo_2.jpg','Prime 1 Studio is a specialty manufacturer of licensed and proprietary collectible products.'),('B0002','Tsume Art','https://upload.wikimedia.org/wikipedia/commons/f/f6/Tsume_Art_full_logo.png','Created in 2010, Tsume is a Luxembourg brand specializing in the design and marketing of high-end collectible statues under official licenses, bearing the likeness of cult characters from the world of animation, manga, cinema and video game.'),('B0003','Iron Studio','https://titantoyz.com/cdn/shop/collections/ed79cb16-4c50-4b99-8df5-64a1e97d52af.png','Iron Studios is a Brazilian company based in São Paulo specializing in the manufacture of collectible statues.'),('B0004','AmiAmi','https://www.amiami.com/images/common/site_logo.png','AmiAmi is a anime related shop.');
CREATE TABLE `ao_category` (
  `category_id` varchar(20) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `cat_id` (`category_id`)
);
INSERT INTO `ao_category` VALUES ('C0001','Figure'),('C0002','Nendoroid'),('C0003','Plush'),('C0004','Playing Card');
CREATE TABLE `ao_customer` (
  `customer_id` varchar(20) NOT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `customer_password` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `customer_address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `c_id_email` (`customer_id`,`email`)
);
INSERT INTO `ao_customer` VALUES ('65ac8e401f8fb','Min Satt Aung','cust@mail.com','customer','$2y$10$TYGH0fzaXKIJitrXvu8SVe524jNmw78RsheGDaO30EibScZzsK52e','09763940667','Yangon');
CREATE TABLE `ao_deliverer` (
  `deliverer_id` varchar(20) NOT NULL,
  `deliverer_name` varchar(50) DEFAULT NULL,
  `deliverer_user` varchar(20) DEFAULT NULL,
  `deliverer_password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `available_days` varchar(1000) DEFAULT NULL,
  `delivery_zone` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`deliverer_id`),
  UNIQUE KEY `de_id` (`deliverer_id`)
);
INSERT INTO `ao_deliverer` VALUES ('DR0001','Royal Express','royal','$2y$10$MnP1CNhv2R3ETWHI.BlHLehQ.t4R4oEZh/pGkWcwfC27me5Lh0Og2','959789234850','7','Myanmar'),('DR0002','DHL','dhl','$2y$10$0rhBolEI0iwvlacjkmPTleDtAGgRMmC7gWN3pZ.KcR6O244/H/HGm','959763940667','7','Myanmar');
CREATE TABLE `ao_delivery` (
  `delivery_id` varchar(20) NOT NULL,
  `deliverer_id` varchar(20) DEFAULT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `estimate_delivery_date` date DEFAULT NULL,
  `tracking_code` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`delivery_id`),
  UNIQUE KEY `de_id_code` (`delivery_id`,`tracking_code`),
  KEY `deliverer_id` (`deliverer_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `ao_delivery_ibfk_1` FOREIGN KEY (`deliverer_id`) REFERENCES `ao_deliverer` (`deliverer_id`),
  CONSTRAINT `ao_delivery_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `ao_order` (`order_id`)
);
INSERT INTO `ao_delivery` VALUES ('D0001','DR0001','O0001','2024-01-29','Royal Express-D0001','PENDING');
CREATE TABLE `ao_film` (
  `film_id` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `film_image` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `film_description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`film_id`),
  UNIQUE KEY `f_id` (`film_id`)
);
INSERT INTO `ao_film` VALUES ('F0001','Naruto','https://m.media-amazon.com/images/M/MV5BZGFiMWFhNDAtMzUyZS00NmQ2LTljNDYtMmZjNTc5MDUxMzViXkEyXkFqcGdeQXVyNjAwNDUxODI@._V1_.jpg','1999-09-21','Naruto is a Japanese manga series written and illustrated by Masashi Kishimoto.'),('F0002','One Piece','https://media.s-bol.com/3pZPnPA4lDvx/lZp0Q6/550x831.jpg','1997-07-22','One Piece is a Japanese manga series written and illustrated by Eiichiro Oda.'),('F0003','Marvel Cinematic Universe','https://lumiere-a.akamaihd.net/v1/images/p_avengersendgame_19751_e14a0104.jpeg','2008-05-01','The Marvel Cinematic Universe is an American media franchise and shared universe centered on a series of superhero films produced by Marvel Studios.'),('F0004','DC Extended Universe','https://static.tvtropes.org/pmwiki/pub/images/01_038.png','2013-06-20','The DC Extended Universe is an American media franchise and shared universe centered on a series of superhero films and a television series produced by DC Studios and distributed by Warner Bros. '),('F0005','Frieren: Beyond Journey\'s End','https://prodimage.images-bn.com/pimages/9781974736201_p0_v1_s1200x630.jpg','2023-09-29','Frieren: Beyond Journey\'s End');
CREATE TABLE `ao_item` (
  `item_id` varchar(20) NOT NULL,
  `category_id` varchar(20) DEFAULT NULL,
  `film_id` varchar(20) DEFAULT NULL,
  `brand_id` varchar(20) DEFAULT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `item_image_1` varchar(255) DEFAULT NULL,
  `item_image_2` varchar(255) DEFAULT NULL,
  `item_image_3` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `item_description` varchar(1000) DEFAULT NULL,
  `scale` varchar(20) DEFAULT NULL,
  `stock_quantity` int DEFAULT NULL,
  `price` int DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `f_id` (`item_id`),
  KEY `category_id` (`category_id`),
  KEY `film_id` (`film_id`),
  KEY `brand_id` (`brand_id`),
  CONSTRAINT `ao_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ao_category` (`category_id`),
  CONSTRAINT `ao_item_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `ao_film` (`film_id`),
  CONSTRAINT `ao_item_ibfk_3` FOREIGN KEY (`brand_id`) REFERENCES `ao_brand` (`brand_id`)
);
INSERT INTO `ao_item` VALUES 
('I0001','C0001','F0003','B0003',' Statue Thor Unleashed Deluxe','https://ironusa.vtexassets.com/arquivos/ids/198185-1536-2048','https://ironusa.vtexassets.com/arquivos/ids/198184-1536-2048','https://ironusa.vtexassets.com/arquivos/ids/198182-1536-2048','2024-01-01',' Statue Thor Unleashed Deluxe - Marvel - Art Scale 1/10 - Iron Studios','1:10',-1,200),
('I0002','C0001','F0003','B0003','Statue Kate Bishop','https://ironusa.vtexassets.com/arquivos/ids/197896-1536-2048','https://ironusa.vtexassets.com/arquivos/ids/197894-1536-2048','https://ironusa.vtexassets.com/arquivos/ids/197907-1536-2048','2024-01-01','Statue Kate Bishop - Hawkeye - Marvel - BDS Art Scale 1/10 - Iron Studios','1:10',0,150),
('I0003','C0001','F0002','B0002','Yamato Ikigai','https://tsumeart-1d733.kxcdn.com/web/image/product.image/5278/image_1024','https://tsumeart-1d733.kxcdn.com/web/image/product.image/5279/image_1024','https://tsumeart-1d733.kxcdn.com/web/image/product.image/5299/image_1024','2024-01-03','Yamato Ikigai figure by Tsume Art','1:10',1,450),
('I0004','C0004','F0002','B0004','UNO One Piece','https://img.amiami.com/images/product/main/121/TOY-IPN-3532.jpg','https://img.amiami.com/images/product/main/121/TOY-IPN-3532.jpg','https://img.amiami.com/images/product/main/121/TOY-IPN-3532.jpg','2023-11-15','Card Game UNO ONE PIECE New World Arc(Released)','1',3,7),
('I0005','C0003','F0001','B0004','Sasuke Plush','https://img.amiami.com/images/product/main/234/GOODS-04381001.jpg','https://img.amiami.com/images/product/main/234/GOODS-04381001.jpg','https://img.amiami.com/images/product/main/234/GOODS-04381001.jpg','2023-09-07','NARUTO Chibi Plush Sasuke Uchiha Childhood Arc(Released)','1',2,15),
('I0006','C0002','F0005','B0004','Frieren','https://img.amiami.com/images/product/main/234/FIGURE-164207.jpg','https://img.amiami.com/images/product/review/234/FIGURE-164207_07.jpg','https://img.amiami.com/images/product/review/234/FIGURE-164207_01.jpg','2024-01-24',`Nendoroid Frieren: Beyond Journey's End Frieren`,'1:10',2,37),
('I0007','C0001','F0004','B0001','Batman versus Batman Who Laughs','https://www.prime1studio.com/on/demandware.static/-/Sites-p1s-master-catalog/default/dw21bc3732/images/UPMDCMT-01DXS/catalog/UPMDCMT-01DXS_a03.jpg','https://www.prime1studio.com/on/demandware.static/-/Sites-p1s-master-catalog/default/dw11b647ee/images/UPMDCMT-01DXS/catalog/UPMDCMT-01DXS_a01.jpg','https://www.prime1studio.com/on/demandware.static/-/Sites-p1s-master-catalog/default/dw26601548/images/UPMDCMT-01DXS/catalog/UPMDCMT-01DXS_a00-04.jpg','2023-12-01','Batman versus Batman Who Laughs favorite (Design by David Finch) DX Bonus Version','1:4',1,1499);
CREATE TABLE `ao_order` (
  `order_id` varchar(20) NOT NULL,
  `customer_id` varchar(20) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `od_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `ao_order_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ao_customer` (`customer_id`)
);
INSERT INTO `ao_order` VALUES ('O0001','65ac8e401f8fb','2024-01-24','SUCCESS');
CREATE TABLE `ao_order_detail` (
  `order_detail_id` varchar(20) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `item_id` varchar(20) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price` int DEFAULT NULL,
  `sub_total` int DEFAULT NULL,
  PRIMARY KEY (`order_detail_id`),
  UNIQUE KEY `od_id` (`order_detail_id`),
  KEY `order_id` (`order_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `ao_order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ao_order` (`order_id`),
  CONSTRAINT `ao_order_detail_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ao_item` (`item_id`)
);
INSERT INTO `ao_order_detail` VALUES ('OD0001','O0001','I0001',1,200,200),('OD0002','O0001','I0002',1,150,150);
CREATE TABLE `ao_payment` (
  `payment_id` varchar(20) NOT NULL,
  `order_id` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `pay_id` (`payment_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `ao_payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ao_order` (`order_id`)
) ;
INSERT INTO `ao_payment` VALUES ('P0001','O0001','kpay','2024-01-24');
CREATE TABLE `ao_purchase` (
  `purchase_id` varchar(20) NOT NULL,
  `supplier_id` varchar(20) DEFAULT NULL,
  `admin_id` varchar(20) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_status` varchar(20) DEFAULT NULL,
  `total_amount` int DEFAULT NULL,
  PRIMARY KEY (`purchase_id`),
  UNIQUE KEY `pur_id` (`purchase_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `ao_purchase_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `ao_supplier` (`supplier_id`),
  CONSTRAINT `ao_purchase_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `ao_admin` (`admin_id`)
);
INSERT INTO `ao_purchase` VALUES ('P0001','S0004','AD001','2024-01-24','PENDING',52),('P0002','S0002','AD001','2024-01-24','PENDING',350),('P0003','S0003','AD001','2024-01-24','PENDING',450),('P0004','S0001','AD001','2024-01-24','PENDING',1499);
CREATE TABLE `ao_purchase_detail` (
  `purchase_detail_id` varchar(20) NOT NULL,
  `purchase_id` varchar(20) DEFAULT NULL,
  `item_id` varchar(20) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price` int DEFAULT NULL,
  `sub_total` int DEFAULT NULL,
  PRIMARY KEY (`purchase_detail_id`),
  UNIQUE KEY `pd_id` (`purchase_detail_id`)
);
INSERT INTO `ao_purchase_detail` VALUES ('PD0001','P0001','I0005',1,15,15),('PD0002','P0001','I0006',1,37,37),('PD0003','P0002','I0001',1,200,200),('PD0004','P0002','I0002',1,150,150),('PD0005','P0003','I0003',1,450,450),('PD0006','P0004','I0007',1,1499,1499);
CREATE TABLE `ao_supplier` (
  `supplier_id` varchar(20) NOT NULL,
  `supplier_name` varchar(20) DEFAULT NULL,
  `supplier_user` varchar(20) DEFAULT NULL,
  `supplier_password` varchar(100) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `a_id_email` (`supplier_id`,`supplier_user`)
);
INSERT INTO `ao_supplier` VALUES ('S0001','Prime 1 Studio','prime','$2y$10$Fr35nziXoIIMHleHdeoHfO9Hn9KMeXS6exEyEi97rykr7pns6shmO','primeone@gmail.com','959783928990'),('S0002','Iron Online Store','iron','$2y$10$S5C7JQWns.HvUJcIFDn0d.UDNsaegbil7MGTrffxKED.1ZvdDcLUu','irononline@mail.com','959872839768'),('S0003','Tsume','tsume','$2y$10$WurpqercNDG86GpBG5hzt.dtD2xqym0t.mSaRqOMBRR3Oqujg51vS','tsume@mail.com','959762837445'),('S0004','AmiAmi','amiami','$2y$10$R3efRAS..sehLxPzHLMske5bcz7.SBL7wQhSnj0he.W0O7r5Y6hO2','amiami@mail.com','959728394638');
