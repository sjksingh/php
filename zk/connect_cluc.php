<?php

// Display error
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

 // Create connection to Oracle
 $username = "system";
 $password = "s4mur4i";
 $host = "cluc.dc3.deem.zone";
 $db_name = "CLUC";

 $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = 1521)))(CONNECT_DATA=(SID = $db_name)))";

 $conn = oci_connect( $username, $password, $db);

 ?>
