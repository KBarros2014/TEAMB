

use TeamB;

create table Category (
            catID integer not null auto_increment ,
            catName varchar(30),
            catPicture varchar(120),
            primary key (catID)
);


create table Products (
        productID integer not null auto_increment,
        productName varchar(30),
        productDescription  varchar(300),
        productPrice decimal (6,2),                             -- nnnn.nn
        catID integer not null,
        primary key (productID),
        foreign key (catID) references category (catID)
);
create table Customer (
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
		-- What if a customer has no orders? or many?
       
);


create table Orders (
      orderId integer not null auto_increment,
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
	  orderPrice decimal (6,2),
	  foreign key (prodcutId) reference product (prodcutId),
	  foreign key (customerId) reference customer (customerId),
	  foreign key (productId) reference order (orderId)
	  foreign key (orderId) reference order (
--	This needs quite a lot more.
--  At a minimum, an orderProductId and an orderid




);


extra code for database structure---------------------
create table Vistor (
       customerId integer not null auto_increment,
       VistorFirstName varchar (20),
       VistorLastName varchar (30),
       VistorPasswordVarchar (50),
       VistorEmailAddress	Varchar(50),
       
);

Create table Payment (
	Payment_id	int (20),
	Amount		int (50),
	Payment_date	Varchar(50),
	Late_update	(int 1);
};



