use playdatabase;

insert into users (name, email, pwCheck, dateCreated, lastLogin) values 
		('Mike Lopez','mike.lopez@cpit.ac.nz','$5$rounds=5000$iLollxzBi1YpCXp3$36.V1YktFFT4TVaow2lxF4m7567sByAwNwpNDrbbH6C','2014-08-01',null);

insert into users (name, email, pwCheck, dateCreated, lastLogin) values 
                ('Mike Lance','lancem@cpit.ac.nz','$5$rounds=5000$cg7cTpEXesmI0cdp$AryMDHoaMzYmqEf3jzzJvitIttQlgXbaluR7PMwu/gA','2014-08-02',null);

insert into administrators (userID) values (1);


insert into people (givenName, familyName) values ('Mike','Lopez');
insert into people (givenName, familyName) values ('Mike','Lance');
insert into people (givenName, familyName) values ('Amit','Sarkar');
insert into people (givenName, familyName) values ('Rob','Oliver');
insert into people (givenName, familyName) values ('Luofeng','Xu');
insert into people (givenName, familyName) values ('Mehdi','Asgarkhani');
insert into people (givenName, familyName) values ('John','McPhee');


insert into categories (name, description) values ('Category one','Description of category one');

insert into products (name, description, price, categoryID) values ('Product one','Description of product one', 123.45, 1);
