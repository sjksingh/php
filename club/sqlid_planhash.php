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
SELECT st.snap_id,  
 to_char(sn.begin_interval_time,'dd. Mon yy hh24:mi') begin_time,  
 st.plan_hash_value, 
 st.optimizer_env_hash_value opt_env_hash,  
 round(st.elapsed_time_delta/1000000,2) elapsed,  
 round(st.cpu_time_delta/1000000,2) cpu,   
 round(st.iowait_delta/1000000,2) iowait 
FROM dba_hist_sqlstat st, 
   dba_hist_snapshot sn 
WHERE st.snap_id=sn.snap_id AND st.sql_id='c8fczbb5b8157' ORDER BY st.snap_id";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<h3>Plan Hash Value for SQLID </h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">snap_id</th>';
print '<th scope="col">begin_time</th>';
print '<th scope="col">plan_hash_value</th>';
print '<th scope="col">opt_env_hash</th>';
print '<th scope="col">elapsed</th>';
print '<th scope="col">cpu</th>';
print '<th scope="col">iowait</th>';
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
