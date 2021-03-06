<?php
$host    = "dc3-trvl-prod-db-mysql09.dc3.google.zone";
$user    = "dba";
$pass    = "rearden1$";
$db_name = "taxiway_gateway";

//create connection
$connection = mysqli_connect($host, $user, $pass, $db_name);

//test if connection failed
if(mysqli_connect_errno()){
    die("connection failed: "
        . mysqli_connect_error()
        . " (" . mysqli_connect_errno()
        . ")");
}

//get results from database
$result = mysqli_query($connection,"SELECT * FROM taxiway_gateway.taxiwaytransactions index(Create_Time)  WHERE DATE_ADD(NOW(), INTERVAL 1 HOUR) > Create_Time;");  
$all_property = array();  //declare an array for saving property

// set the header
print '<h3>Connected to Production - GDS-DB</h3>';
//showing property
echo '<table border="1">
        <tr>';  //initialize table tag
while ($property = mysqli_fetch_field($result)) {
    echo '<td>' . $property->name . '</td>';  //get field name for header
    array_push($all_property, $property->name);  //save those to array
}
echo '</tr>'; //end tr tag

//showing all data
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    foreach ($all_property as $item) {
        echo '<td>' . $row[$item] . '</td>'; //get items using property value
    }
    echo '</tr>';
}
echo "</table>";
?>
