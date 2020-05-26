<?php

// Create connection to Oracle
$username = "system";
$password = "s4mur4i";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = clud.dc3.deem.zone)(PORT = 1599)))(CONNECT_DATA=(SID = clud)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
  SELECT dbs.BEGIN_INTERVAL_TIME,
         dbo.snap_id, 
         dbo.value
  FROM DBA_HIST_OSSTAT dbo, dba_hist_snapshot dbs
 WHERE dbo.snap_id = dbs.snap_id
 AND dbo.stat_name = 'LOAD'
 AND dbs.BEGIN_INTERVAL_TIME > sysdate -1
 ORDER BY dbs.BEGIN_INTERVAL_TIME";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUD</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">BEGIN_INTERVAL_TIME</th>';
print '<th scope="col">SNAP_ID</th>';
print '<th scope="col">VALUE</th>';
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
