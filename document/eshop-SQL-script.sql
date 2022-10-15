create database eshop
go

use eshop
go

create table users
(
    id int,
    email varchar(255),
    first_name varchar(255),
    last_name varchar(255),
    password varchar(255),
    role varchar(10),
    active int,
    create_at datetime,
    update_at datetime,
    history_keyword text,
    PRIMARY KEY(id)
);


create table products
(
    id int,
    name varchar(255),
    remaining int,
    manufacturer varchar(20),
    price int,
    screen_size float,
    cpu varchar(30),
    ram int,
    rom int,
    graphic_card varchar(30),
    content text,
    create_at datetime,
    update_at datetime,
    metadata text,
    PRIMARY KEY(id)
);


create table categories
(
	id int,
    name varchar(30),
    image varchar(255),
    category_parent int,
    PRIMARY KEY(id)
);


create table vouchers
(
	id int,
    voucher_code varchar(255),
    create_at datetime,
    expire_at datetime,
    minimum_spend int,
    decreased_spend int,
    type varchar(10),
    PRIMARY KEY(id)
);

create table product_category
(
	id int,
    product_id int,
    category_id int,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    PRIMARY KEY(id)
);


create table address_book
(
	id int,
    name varchar(30),
    phone varchar(15),
    address varchar(255),
    user_id int,
    FOREIGN KEY (user_id) REFERENCES users(id),
    PRIMARY KEY(id)
);


create table orders
(
	id BINARY(16),
    user_id int,
    name varchar(30),
    phone varchar(15),
    address varchar(255),
    description varchar(255),
    create_at datetime,
    update_at datetime,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


create table shippers
(
	id int,
    name varchar(30),
    phone varchar(15),
    password varchar(255),
    PRIMARY KEY (id)
);


create table order_content
(
	id int,
    order_id BINARY(16),
    product_id int,
    quantity int,
    delivery_status varchar(20),
	review_status int,
    total_price int,
	shipper_id int,
    PRIMARY KEY (id),
    FOREIGN KEY (shipper_id) REFERENCES shippers(id),
    FOREIGN KEY (order_id) REFERENCES orders(id),
	FOREIGN KEY (product_id) REFERENCES products(id)
);

create table voucher_wallet
(
	id int,
    user_id int,
    voucher_id int,
    status varchar(10),
    PRIMARY KEY (id),
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


create table product_photo
(
	id int,
    product_id int,
    url varchar(255),
    is_avatar int,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


create table product_review
(
	id int,
    product_id int,
    user_id int,
    content varchar(255),
    rate int,
    create_at datetime,
    update_at datetime,
    PRIMARY KEY (id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


create table product_review_photo
(
	id int,
    review_id int,
    url varchar(255),
    PRIMARY KEY (id),
    FOREIGN KEY (review_id) REFERENCES product_review(id)
);

create table configuration
(
	id int,
    name varchar(10),
    data text,
    PRIMARY KEY (id)
);