use teamb;

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
       primary key (customerId)
    
    
);


create table orders (
      orderId integer not null auto_increment,
      orderQuantity integer ,
      orderPrice decimal (6,2),
	  orderDate DATE ,

	  customerId integer not null,
      
      primary key (orderId),

      foreign key (customerId) references customer (customerId)

);

create table orderProduct (
	  quantity integer,
      orderId int,
      productId int,
	  orderPrice decimal (6,2),
	  foreign key (productId) references products(productId),
      foreign key (orderId) references orders (orderId)





);


-- extra code for database structure---------------------


create table Payment (
	Payment_id	int (20),
    custId int , 
	Amount		decimal  (6, 2),
	Payment_date	date ,
	Lastupdate	date,
	paymentType int  
};


create shipment (
	--to beworkde on


)
