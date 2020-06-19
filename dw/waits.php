<?php

// auto regresh 5 sec
$url1=$_SERVER['REQUEST_URI'];
header("Refresh: 10; URL=$url1");

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dw.dc3.google.zone)(PORT = 1521)))(CONNECT_DATA=(SID = dw)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
    SELECT 
      s.sid,
      s.module, 
      sql_hash_value ,
      sql_id, 
      event,
      wait_class , 
      s.seconds_in_wait sec,
      a.CONSISTENT_GETS, 
      nvl(to_char(blocking_session) ,'NB' )
from
v\$sess_io  a,
v\$session      s
 where  a.sid=s.sid  and
        s.status='ACTIVE'
        and s.wait_class != 'Idle'
        order by event
   ";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - DW</h3>';
print '<h3>Database Waits....</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">SID</th>';
print '<th scope="col">MODULE</th>';
print '<th scope="col">SQL HASH VALUE</th>';
print '<th scope="col">SQL ID</th>';
print '<th scope="col">EVENTS</th>';
print '<th scope="col">WAIT CLASS</th>';
print '<th scope="col">Waits in SECs</th>';
print '<th scope="col">CONSISTENT GETS</th>';
print '<th scope="col">BLOCKING SESSION</th>';
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
