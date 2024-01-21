CREATE TABLE ao_admin
(
    admin_id varchar(20) NOT NULL PRIMARY KEY,
    fullname varchar(100),
    username varchar(100),
    admin_password varchar(100),
    email varchar(30),
    phone_number varchar(20),
    CONSTRAINT ad_id_user UNIQUE (admin_id, username)
);
CREATE TABLE ao_customer
(
    customer_id VARCHAR(20) NOT NULL PRIMARY KEY,
    full_name VARCHAR(50),
    email VARCHAR(20),
    username VARCHAR(20),
    customer_password VARCHAR(100),
    phone VARCHAR(20),
    customer_address VARCHAR(50),
    CONSTRAINT c_id_email UNIQUE (customer_id,email)
);
CREATE TABLE ao_category
(
    category_id VARCHAR(20) NOT NULL PRIMARY KEY,
    category_name VARCHAR(50),
    category_image VARCHAR(255),
    category_description VARCHAR(1000),
    CONSTRAINT cat_id UNIQUE (category_id)
);
CREATE TABLE ao_brand
(
    brand_id VARCHAR(20) NOT NULL PRIMARY KEY,
    brand_name VARCHAR(50),
    brand_image VARCHAR(255),
    brand_description VARCHAR(1000),
    CONSTRAINT cat_id UNIQUE (brand_id)
);
CREATE TABLE ao_film
(
    film_id VARCHAR(20) NOT NULL PRIMARY KEY,
    title VARCHAR(50),
    film_image VARCHAR(255),
    release_date DATE,
    film_description VARCHAR(1000),
    CONSTRAINT f_id UNIQUE (film_id)
);
CREATE TABLE ao_item
(
    item_id VARCHAR(20) NOT NULL PRIMARY KEY,
    category_id VARCHAR(20),
    film_id VARCHAR(20),
    brand_id VARCHAR(20),
    item_name VARCHAR(50),
    item_image_1 VARCHAR(255),
    item_image_2 VARCHAR(255),
    item_image_3 VARCHAR(255),
    release_date DATE,
    item_description VARCHAR(1000),
    scale VARCHAR(20),
    stock_quantity INT,
    price INT,
    FOREIGN KEY (category_id) REFERENCES ao_category (category_id),
    FOREIGN KEY (film_id) REFERENCES ao_film (film_id),
    FOREIGN KEY (brand_id) REFERENCES ao_brand (brand_id),
    CONSTRAINT f_id UNIQUE (item_id)
);
CREATE TABLE ao_supplier
(
    supplier_id VARCHAR(20) NOT NULL PRIMARY KEY,
    supplier_name VARCHAR(20),
    supplier_user VARCHAR(20),
    supplier_password VARCHAR(100),
    email VARCHAR(20),
    phone VARCHAR(20),
    CONSTRAINT a_id_email UNIQUE (supplier_id,supplier_user)
);
CREATE TABLE ao_purchase (
    purchase_id VARCHAR(20) NOT NULL PRIMARY KEY ,
    supplier_id VARCHAR(20),
    admin_id VARCHAR(20),
    purchase_date DATE,
    purchase_status VARCHAR(20),
    total_amount INT,
    FOREIGN KEY (supplier_id) REFERENCES ao_supplier (supplier_id),
    FOREIGN KEY (admin_id) REFERENCES ao_admin (admin_id),
    CONSTRAINT pur_id UNIQUE (purchase_id)
);
CREATE TABLE ao_purchase_detail (
    purchase_detail_id VARCHAR(20) NOT NULL PRIMARY KEY ,
    purchase_id VARCHAR(20),
    item_id VARCHAR(20),
    quantity INT,
    unit_price INT,
    sub_total INT,
    CONSTRAINT pd_id UNIQUE (purchase_detail_id)
  );
  CREATE TABLE ao_order (
    order_id VARCHAR(20) NOT NULL PRIMARY KEY,
    customer_id VARCHAR(20),
    order_date DATE,
    order_status VARCHAR(20),
    FOREIGN KEY (customer_id) REFERENCES ao_customer (customer_id),
    CONSTRAINT od_id UNIQUE (order_id)
  );
  CREATE TABLE ao_order_detail (
    order_detail_id VARCHAR(20) NOT NULL PRIMARY KEY ,
    order_id VARCHAR(20),
    item_id VARCHAR(20),
    quantity INT,
    unit_price INT,
    sub_total INT,
    FOREIGN KEY (order_id) REFERENCES ao_order (order_id),
    FOREIGN KEY (item_id) REFERENCES ao_item (item_id),
    CONSTRAINT od_id UNIQUE (order_detail_id)
  );
  CREATE TABLE ao_payment (
    payment_id VARCHAR(20) NOT NULL PRIMARY KEY ,
    order_id VARCHAR(20),
    payment_method VARCHAR(50),
    payment_date DATE,
    FOREIGN KEY (order_id) REFERENCES ao_order (order_id),
    CONSTRAINT pay_id UNIQUE (payment_id)
  );
  CREATE TABLE ao_deliverer (
    deliverer_id VARCHAR(20) NOT NULL PRIMARY KEY,
    deliverer_name VARCHAR(50),
    deliverer_user VARCHAR(20),
    deliverer_password VARCHAR(20),
    phone VARCHAR(30),
    available_days VARCHAR(1000),
    delivery_zone VARCHAR(1000),
    CONSTRAINT de_id UNIQUE (deliverer_id)
  );
  CREATE TABLE ao_delivery (
    delivery_id VARCHAR(20) NOT NULL PRIMARY KEY,
    deliverer_id VARCHAR(20),
    order_id VARCHAR(20),
    estimate_delivery_date DATE,
    tracking_code VARCHAR(20),
    delivery_status VARCHAR(20),
    FOREIGN KEY (deliverer_id) REFERENCES ao_deliverer (deliverer_id),
    FOREIGN KEY (order_id) REFERENCES ao_order (order_id),
    CONSTRAINT de_id_code UNIQUE (delivery_id, tracking_code)
  );