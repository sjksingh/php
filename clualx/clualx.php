<?php

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.50.96.105)(PORT = 1521)))(CONNECT_DATA=(SID = clua)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "select module,count(1) from v\$session where module not in ('Streams','KTSJ','apache2@dc3-trvl-prod-db-prcmon01 (TNS V1-V3)','MMON_SLAVE')  group by module order by module";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - lx-CLUA</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">Service Name</th>';
print '<th scope="col">Sessions Count</th>';
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
