<?php

// auto regresh 5 sec
$url1=$_SERVER['REQUEST_URI'];
header("Refresh: 5; URL=$url1");

// Create connection to Oracle
$username = "system";
$password = "s4mur4i";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.50.96.107)(PORT = 1521)))(CONNECT_DATA=(SID = club)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
  SELECT
   s.sid                       sid
  , s.serial#                   serial
  , to_char(s.logon_time,'dd/mm/yyyy hh24:mi:ss')   slogon_time
  , lpad(s.status,9)            session_status
  , lpad(s.username,14)         oracle_username
  , lpad(s.osuser,12)           os_username
  , lpad(p.spid,7)              os_pid
  , s.event                     session_event
  , s.module                   session_module
  , SUBSTR(sa.sql_text, 1, 600) current_sql
FROM 
    v\$process p
  , v\$session s
  , v\$sqlarea sa
WHERE
      p.addr (+)       =  s.paddr
  AND s.sql_address    =  sa.address(+) 
  AND s.sql_hash_value =  sa.hash_value(+)
  AND s.audsid         <> userenv('SESSIONID')
  AND s.username       IS NOT NULL
  AND s.status         = 'ACTIVE'
  ORDER BY sid
   ";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">SID</th>';
print '<th scope="col">SERIAL#</th>';
print '<th scope="col">LOGONTIME</th>';
print '<th scope="col">SESSIONSTTAUS</th>';
print '<th scope="col">USERNAME</th>';
print '<th scope="col">OSUSER</th>';
print '<th scope="col">OSPID</th>';
print '<th scope="col">EVENT</th>';
print '<th scope="col">MODULE</th>';
print '<th scope="col">SQLTEXT</th>';
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
