<?php

// auto regresh 5 sec
$url1=$_SERVER['REQUEST_URI'];
header("Refresh: 10; URL=$url1");

// connect to Oracle..
require("/var/www/html/db/zk/connect_cluc.php");

if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

// display the header.....
echo "<img valgin='bottom' src='https://static1.squarespace.com/static/589a5e8286e6c034c4ad55fe/t/58dae19e6a49639d84fb005a/1500064110650/?format=1500w' width=10% height=10%  alt='Deem' /><font color='blue' size=6> Production $db_name Environment</font></td></tr></table>
";

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
print "<h3>Active SQLs - $db_name</h3>";
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th bgcolor="YELLOW" scope="col">SID</th>';
print '<th bgcolor="YELLOW" scope="col">SERIAL#</th>';
print '<th bgcolor="YELLOW" scope="col">LOGONTIME</th>';
print '<th bgcolor="YELLOW" scope="col">SESSIONSTTAUS</th>';
print '<th bgcolor="YELLOW" scope="col">USERNAME</th>';
print '<th bgcolor="YELLOW" scope="col">OSUSER</th>';
print '<th bgcolor="YELLOW" scope="col">OSPID</th>';
print '<th bgcolor="YELLOW" scope="col">EVENT</th>';
print '<th bgcolor="YELLOW" scope="col">MODULE</th>';
print '<th bgcolor="YELLOW" scope="col">SQLTEXT</th>';
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
