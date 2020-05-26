<?php

// Create connection to Oracle
$username = "system";
$password = "s4mur4i";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = club.dc3.deem.zone)(PORT = 1521)))(CONNECT_DATA=(SID = club)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
SELECT count(*), owner, object_type
FROM dba_objects
WHERE owner IN ('TALARIS_PROD','ROLE_ADMIN','STATICDATA')
GROUP by owner,object_type
order by 2
";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<h3>List Object Type Count  </h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">COUNT</th>';
print '<th scope="col">OWNER</th>';
print '<th scope="col">OBJECT TYPE</th>';
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
