<?php
// auto regresh 5 sec
//$url1=$_SERVER['REQUEST_URI'];
//header("Refresh: 5; URL=$url1");

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.50.96.109)(PORT = 1521)))(CONNECT_DATA=(SID = cluc)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "select substr(b.module,1,10) module,
       substr(b.username,1,10) username,
       ltrim(rtrim(substr(b.machine,1,10))) machine,
       substr(ltrim(rtrim(a.sql_address)),1,12) sql_address,
       substr(ltrim(rtrim(a.sql_hash_value)),1,12) sql_hash_value ,
       rtrim(ltrim(elapsed_seconds)) esec ,
       ltrim(rtrim(substr(a.opname,1,15))) opname,
       rtrim(ltrim(substr(a.TARGET,1,35))) TARGET,
       substr(ltrim(rtrim(round(a.SOFAR*100 / a.TOTALWORK,0))),1,5) || '%' as DONE,
       ltrim(rtrim(a.TIME_REMAINING)) tm,
       ltrim(rtrim(to_char(a.start_time,'YYYY/MM/DD HH24:MI:SS'))) START_TIME,
       substr(c.sql_text,1,85) sql_text
  from
  v\$session_longops a,
  v\$session b,
  v\$sqlarea c
  where
      b.sql_address = c.address
  and a.sql_address = c.address
  and a.sid=b.sid
  and a.TIME_REMAINING > 40";
//  order by a.TIME_REMAINING desc";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUC</h3>';
print '<h3>LongOPS Report</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">module</th>';
print '<th scope="col">username</th>';
print '<th scope="col">machine</th>';
print '<th scope="col">sql_address</th>';
print '<th scope="col">sql_hash_value</th>';
print '<th scope="col">esec</th>';
print '<th scope="col">opname</th>';
print '<th scope="col">TARGET</th>';
print '<th scope="col">%DONE</th>';
print '<th scope="col">Time REMAINING</th>';
print '<th scope="col">START_TIME</th>';
print '<th scope="col">sql_text</th>';
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
