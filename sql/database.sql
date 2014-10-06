use teamb;
--Customer table store all registed customer,before registed the new ewgisted will compare current customer table database it has been registed or not, and then registed customer.
--uniqe customer ID
create table customer (
       customerId integer not null auto_increment,
       customerFirstName varchar (20),
       customerLastName varchar (30),
       customerAddress varchar (100),
       customerCity varchar (20),
       customerPostCode varchar (4),
       customerEmail varchar (50),
       isBusinessAccount Varchar(1),			--Alex:business account holder,reutrn ture/false
       primary key (customerId)
   );

--A table list current product species
create table category (
            catID integer not null auto_increment ,
            catName varchar(30),
							-- I have removed catPic because it belongs to product Tabl
	primary key (catID)
			
);

--A table list all single prodcts detail
create table products (
        productID integer not null auto_increment,
        productName varchar(30),
        productDescription  varchar(300),
        productPrice decimal (6,2), 
		productPic varchar (255),-- nnnn.nn
		isAvailability vachar (1), 		--Alex:return ture/false
		deliveryTime int (10), 			--Alex:estiated delivery lead time,reutrn number tto delivery table
		product Size varchar (30),		--Alex:check the web side 
		productQuantity int (100),		--Alex:check isAvailability before eaaste;it DIFFERENT to paymentQuantity
        catID integer not null,
        primary key (productID),
        foreign key (catID) references category (catID)
);

--A table list all order has been scuess make
create table orders (
      orderId integer not null auto_increment,
      orderQuantity integer ,
      orderPrice decimal (6,2),
      orderIsScuess varchar (1),			--Alex:return ture/false, is fail for order,it will still store in database wih THIS orderId
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

--each order's payment,will falte if order table return orderIsScuess false(0)
create table Payment (
	paymentId not null,
    	paymentQuantity int (100);
	totalamount	decimal  (6, 2),
	paymentDate	date,
	paymentType	varchar (10),
	foreign key (paymentId) references orders (orderId)  
};


create table shipment (
	shipmentId not null;
	foreign key (shipmentId) references orders paymentId)  

)
