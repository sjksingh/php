<!-- Start on Menu -->
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}
</style>
</head>
<body>

<div class="topnav">
  <a class="active" href="#home">Home</a> 
  <a href="http://10.50.29.76/db/expense/activeSQL.php">Expense-DB</a>
  <a href="http://10.50.29.76/db/hotel/activeSQL.php">Hotel-DB</a>
  <a href="http://10.50.29.76/db/ach/activeSQL.php">ACH-DB</a> 
  <a href="http://10.50.29.76/db/trs/activeSQL.php">TRS-DB</a>
  <a href="http://10.50.29.76/db/ehs/activeSQL.php">EHS-DB</a>
  <a href="http://10.50.29.76/db/gds/activeSQL.php">GDS-DB</a>
  <a href="http://10.50.29.76/db/odi/activeSQL.php">ODI-DB</a>
</div>

<div style="padding-left:16px">
  <h2><center>Database Active Session Report</center></h2> 
  <p></p>
</div>

</body>
</html>
<!-- End on Menu -->

<!-- Start of Data Fecth -->
<?php
$host    = "dc3-trvl-prod-db-mysql01.dc3.google.zone";
$user    = "dba";
$pass    = "rearden1$";
$db_name = "ehs_db";

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
$result = mysqli_query($connection,"SELECT * FROM information_schema.processlist WHERE command != 'Sleep' ORDER BY id;");
$all_property = array();  //declare an array for saving property

// set the header 
print '<h3>Connected to Production - EHSDB</h3>';
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
