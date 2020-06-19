<?php

// auto regresh 5 sec
$url1=$_SERVER['REQUEST_URI'];
//header("Refresh: 10; URL=$url1");

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
 SELECT * FROM (
SELECT sql_id, sum(disk_reads_delta) disk_reads_delta,
sum(disk_reads_total) disk_reads_total,
sum(executions_delta) execs_delta,
sum(executions_total) execs_total
FROM dba_hist_sqlstat
GROUP BY sql_id
ORDER BY 2 desc)
WHERE rownum <= 50 
   ";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<h3>SQL with high Disk-Read </h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">SQLID</th>';
print '<th scope="col">disk_reads_delta</th>';
print '<th scope="col">disk_reads_total</th>';
print '<th scope="col">execs_delta</th>';
print '<th scope="col">execs_total</th>';
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
