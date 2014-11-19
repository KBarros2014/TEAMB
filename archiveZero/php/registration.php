//Naicy George

<?php
mysql_connect("localhost","root","");
mysql_select_db("users_db");
if(isset($POST['Register']))
{
$firstname=$_POST['Firstname'];

$lastname=$_POST['Lastname'];

$companyname=$_POST['Company'];

$building=$_POST['Building'];

$Streetnumber=$_POST['Streetnumber'];

$streetname=$_POST['Streetname'];

$suburb=$_POST['sururb'];

$city/town=$_POST['city'];

$postcode=$_POST['Postalcode'];

$daytimephone=$_POST['Phonearea'];

$mobilephone=$_POST['mobile'];

$Email address=$_POST['email'];

$Re enter email address=$_POST['email confirmation'];

$I would like to receive emails on your latest specials=$_POST[''];

$username=$_POST['username'];

$Password=$_POST['password'];

$Confirm password=$_POST['password'];
