CREATE TABLE `Admin` (
  `adminId` varchar(10) PRIMARY KEY,
  `name` varchar(100),
  `username` varchar(100),
  `password` varchar(100),
  `email` varchar(30),
  `phoneNumber` varchar(20)
);

CREATE TABLE `Supplier` (
  `supplierId` varchar(10) PRIMARY KEY,
  `name` varchar(100),
  `username` varchar(100),
  `password` varchar(100),
  `email` varchar(30),
  `phoneNumber` varchar(20)
);

CREATE TABLE `Purchase` (
  `purchaseId` varchar(10) PRIMARY KEY,
  `supplierId` varchar(10),
  `adminId` varchar[10],
  `purchaseDate` date,
  `status` varchar[10],
  `totalAmount` number
);

CREATE TABLE `PurcahseDetail` (
  `purchaseDetailId` varchar[10] PRIMARY KEY,
  `purchaseId` varchar(10),
  `itemId` varchar[10],
  `quantity` number,
  `unitPrice` number,
  `subTotal` number
);

CREATE TABLE `Category` (
  `categoryId` varchar[10] PRIMARY KEY,
  `categoryName` varchar[50],
  `description` varchar[1000]
);

CREATE TABLE `Film` (
  `filmId` varchar[10] PRIMARY KEY,
  `title` varchar[50],
  `releaseDate` date,
  `description` varhcar[1000]
);

CREATE TABLE `Brand` (
  `brandId` varchar[10] PRIMARY KEY,
  `brandName` varchar[50],
  `description` varchar[1000]
);

CREATE TABLE `Item` (
  `itemId` varchar[10] PRIMARY KEY,
  `categoryId` varchar[10],
  `filmId` varchar[10],
  `brandId` varchar[10],
  `name` varchar[50],
  `description` varchar[1000],
  `stockQuantity` number,
  `price` number
);

CREATE TABLE `ItemDetail` (
  `itemDetailId` varchar[10] PRIMARY KEY,
  `itemId` varchar[10],
  `scale` varchar[10],
  `releaseDate` date,
  `manufacturer` varchar[50]
);

CREATE TABLE `Customer` (
  `customerId` varchar(10) PRIMARY KEY,
  `name` varchar(100),
  `username` varchar(100),
  `password` varchar(100),
  `email` varchar(30),
  `phoneNumber` varchar(20)
);

CREATE TABLE `Order` (
  `orderId` varchar[10] PRIMARY KEY,
  `customerId` varchar[10],
  `orderDate` date,
  `status` ENUM ('Pending', 'Approved', 'Shipped', 'Delivered')
);

CREATE TABLE `OrderDetail` (
  `orderDetailId` varchar[10] PRIMARY KEY,
  `orderId` varchar[10],
  `itemId` varchar[10],
  `quantity` number,
  `unitPrice` number,
  `subTotal` number
);

CREATE TABLE `Payment` (
  `paymentId` varchar[10] PRIMARY KEY,
  `orderId` varchar[10],
  `paymentMethod` varhcar[100],
  `paymentDate` date
);

CREATE TABLE `Deliverer` (
  `delivererId` varchar[10] PRIMARY KEY,
  `name` varchar[100],
  `contactNumber` varchar[30],
  `availableDays` varchar[100],
  `deliveryZone` varchar[100]
);

CREATE TABLE `Delivery` (
  `deliveryId` varchar[10] PRIMARY KEY,
  `delivererId` varchar[10],
  `orderId` varchar[10],
  `estimateDeliveryDate` date,
  `trackingCode` varchar[20]
);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`supplierId`) REFERENCES `Supplier` (`supplierId`);

ALTER TABLE `Purchase` ADD FOREIGN KEY (`adminId`) REFERENCES `Admin` (`adminId`);

ALTER TABLE `PurcahseDetail` ADD FOREIGN KEY (`purchaseId`) REFERENCES `Purchase` (`purchaseId`);

ALTER TABLE `Item` ADD FOREIGN KEY (`categoryId`) REFERENCES `Category` (`categoryId`);

ALTER TABLE `Item` ADD FOREIGN KEY (`filmId`) REFERENCES `Film` (`filmId`);

ALTER TABLE `Item` ADD FOREIGN KEY (`brandId`) REFERENCES `Brand` (`brandId`);

ALTER TABLE `ItemDetail` ADD FOREIGN KEY (`itemId`) REFERENCES `Item` (`itemId`);

ALTER TABLE `Order` ADD FOREIGN KEY (`customerId`) REFERENCES `Customer` (`customerId`);

ALTER TABLE `OrderDetail` ADD FOREIGN KEY (`orderId`) REFERENCES `Order` (`orderId`);

ALTER TABLE `OrderDetail` ADD FOREIGN KEY (`itemId`) REFERENCES `Item` (`itemId`);

ALTER TABLE `Payment` ADD FOREIGN KEY (`orderId`) REFERENCES `Order` (`orderId`);

ALTER TABLE `Delivery` ADD FOREIGN KEY (`delivererId`) REFERENCES `Deliverer` (`delivererId`);

ALTER TABLE `Delivery` ADD FOREIGN KEY (`orderId`) REFERENCES `Order` (`orderId`);
