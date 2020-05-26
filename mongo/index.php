<?php
 
/**
    User name : admu
    Password : new_pass
    MongoDB host : localhost
    MongoDB port : 27017
    Database : university
*/
 
//$server = "mongodb://cars_app_ro:readonly@dc3-trvl-prod-db-mongodb01:10000/cars_db";
//$server = new MongoClient("mongodb://cars_app_ro:readonly@dc3-trvl-prod-db-mongodb01:10000/cars_db");
$server = new MongoDB\Driver\Manager("mongodb://dba:manager@dc3-trvl-prod-db-mongodb01:10000/admin");

/**
    Remove username and password, if you want
    to connect to an unauthenticated MongoDB database.
    See the example code below
*/
// $server = "mongodb://localhost:27017/university";
 
// Connecting to server
$c = new MongoDB\Driver\Manager( $server );
 
if($c->connected)
    echo "Connected successfully";
else
    echo "Connection failed";
 
?>
