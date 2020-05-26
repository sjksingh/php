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
//$server = new MongoDB\Driver\Manager("mongodb://dba:manager@dc3-trvl-prod-db-mongodb01:10000/admin");

//var_dump($server); 

try {

    $mng = new MongoDB\Driver\Manager("mongodb://dba:manager@dc3-trvl-prod-db-mongodb01:10000/admin");

    $stats = new MongoDB\Driver\Command(["dbstats" => 1]);
    $res = $mng->executeCommand("testdb", $stats);
    
    $stats = current($res->toArray());

    print_r($stats);

} catch (MongoDB\Driver\Exception\Exception $e) {

    $filename = basename(__FILE__);
    
    echo "The $filename script has experienced an error.\n"; 
    echo "It failed with the following exception:\n";
    
    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";       
}
    
?>
