<?php

// auto regresh 5 sec
//$url1=$_SERVER['REQUEST_URI'];
//header("Refresh: 10; URL=$url1");

// Create connection to Oracle
$username = "system";
$password = "s4mur4i";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = mgmt.dc3.deem.zone)(PORT = 1521)))(CONNECT_DATA=(SID = mgmt)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
SELECT
  count(1),
  module,
  machine,
  username
FROM
 v\$session
--WHERE module like 'webse%'
WHERE USERNAME NOT IN ('SYS','SYSTEM')
group by module,machine,username
order by module
   ";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - MGMT</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">Session Count</th>';
print '<th scope="col">MODULE</th>';
print '<th scope="col">MACHINE</th>';
print '<th scope="col">USERNAME</th>';
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
