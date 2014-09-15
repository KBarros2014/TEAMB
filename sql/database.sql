

use TeamB;

create table category (
            catID integer not null auto_increment ,
            catName varchar(30),
            catPicture varchar(120),
            primary key (catID)
);


create table products (
        productID integer not null auto_increment,
        productName varchar(30),
        productDescription  varchar(300),
        productPrice decimal (6,2),                             -- nnnn.nn
        catID integer not null,
        primary key (productID),
        foreign key (catID) references category (catID)
);
create table customer (
       customerId integer not null auto_increment,
       customerFirstName varchar (20),
       customerLastName varchar (30),
       customerAddress varchar (100),
       customerCity varchar (20),
       customerPostCode varchar (4),
       customerEmail varchar (50),
       primary key (customerId),
       -- (ML) syntax error on following line 
       -- foreign key (orderId) reference order (orderId)
       -- it should be
		foreign key (orderId) references orders (orderId)
		-- however should we really store an order ID on a customer?
		-- What if a cuatomer has no orders? or many?
       
);


create table orders (
      orderId integer not numm auto_increment,
      orderQuantity integer ,
      orderPrice decimal (6,2),
-- (ML) field added (referred to in foreign key)
	  customerId integer not null,
      
      primary key (orderId),
-- (ML) syntax error corrected
--      foreign key (customerId) reference customer (customerId)
      foreign key (customerId) references customer (customerId)
      
-- (ML) I don't think price or quantity belongs here. Thst's for the order 
--     details table (orderProduct in your design).
--     However, things like order date, delivery information, etc do belong here
);

create table orderProduct (
	  quantity integer,
	  foreign key (productId) reference order (orderId)
	  foreign key (orderId) reference order (
--	This needs quite a lot more.
--  At a minimum, an orderProductId and an orderid




);


