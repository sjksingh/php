<?php

// Create connection to Oracle
$username = "system";
$password = "s4mur4i";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = cluc.dc3.deem.zone)(PORT = 1521)))(CONNECT_DATA=(SID = cluc)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
 SELECT
    a.indx + 1  file#
  , b.name      filename
  , a.count     ct
  , a.time      time
  , a.time/(DECODE(a.count,0,1,a.count)) avg
FROM
    x\$kcbfwait   a
  , v\$datafile   b
WHERE
      indx < (SELECT count(*) FROM v\$datafile)
  AND a.indx+1 = b.file#
ORDER BY a.time desc  
  ";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUC</h3>';
print '<h3>Busy Buffer Wait Per File  </h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">File Name</th>';
print '<th scope="col">File #</th>';
print '<th scope="col">Waits (Count)</th>';
print '<th scope="col">Time</th>';
print '<th scope="col">Avg Time</th>';
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
