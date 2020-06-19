<?php

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = club.dc3.google.zone)(PORT = 1521)))(CONNECT_DATA=(SID = club)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
select owner, trigger_name, table_owner, table_name, triggering_event from all_triggers where owner = 'TALARIS_PROD' order by 2
";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<h3>List All Triggers owned by Talaris Prod...</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">OWNER</th>';
print '<th scope="col">TRIGGER NAME</th>';
print '<th scope="col">TABLE OWNER</th>';
print '<th scope="col">TABLE NAME</th>';
print '<th scope="col">TRIGGERING EVENT</th>';
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
