<?php

// auto regresh 5 sec
//$url1=$_SERVER['REQUEST_URI'];
//header("Refresh: 10; URL=$url1");
// if purge - ALTER TABLE "TALARIS_PROD"."PROVIDER_TRANSACTION" DROP PARTITION "PROVIDER_2019_01_15" UPDATE GLOBAL INDEXES;

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = clud.dc3.google.zone)(PORT = 1599)))(CONNECT_DATA=(SID = clud)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
SELECT
  PARTITION_NAME,
  NUM_ROWS
FROM dba_tab_partitions
WHERE table_name='PROVIDER_TRANSACTION'
order by PARTITION_NAME";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Partition info on PROVIDER_TRANSACTION CLUD</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">PARTITION_NAME</th>';
print '<th scope="col">NUM_ROWS</th>';
print '</tr>';
while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC))
{print '<tr>';
  foreach ($row as $item) {
     print '<td>'.($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp').'</td>';
 }
 print '</tr>';
}
print '</table>';
oci_free_statement($stid);
oci_close($conn);
?>
