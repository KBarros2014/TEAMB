use playdatabase;


drop table if exists products;	
drop table if exists categories;

drop table if exists administrators;
drop table if exists customers;
drop table if exists users;
drop table if exists people;

create table users (
	userID  	integer not null auto_increment, 
	name  		varchar(64), 
	email 		varchar(64), 
	pwCheck 	varchar(75),
	customerID	integer,
	dateCreated datetime, 
	lastLogin 	datetime, 
	primary key (userID), 
	unique key	(email)
	);

create table administrators (
	userID 		int not null auto_increment, 
	primary key 	(userID)
	);



create table people (
	personID 	int not null auto_increment, 
	givenName 	varchar(40), 
	familyName 	varchar(40), 
	primary key 	(personID)
	);

create table categories (
	categoryID 	integer not null auto_increment, 
	name 		varchar(40), 
	description varchar(200), 
	primary key (categoryID) 
	);

create table products (
	productID 	integer not null auto_increment,
	name 		varchar(40) not null,
	description varchar(300) not null,
	price 		decimal(6,2) not null, 
	categoryID 	integer not null,
	primary key (productID),
	foreign key (categoryID) references categories (categoryID)
	);

create table customers (
       customerId 	integer not null auto_increment,
       address 		varchar (200) not null,
	   userID		integer,
       primary key 	(customerId),
	   foreign key 	(userID) references users (userID)
   );
