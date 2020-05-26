<?php

// Display error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// connect to Oracle..
require("/var/www/html/db/zk/connect_dw.php");

if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

// display the header.....
echo "<img valgin='bottom' src='https://static1.squarespace.com/static/589a5e8286e6c034c4ad55fe/t/58dae19e6a49639d84fb005a/1500064110650/?format=1500w' width=10% height=10%  alt='Deem' /><font color='blue' size=6> Production $db_name Environment</font></td></tr></table>
";

$sql = "SELECT sid,
         serial#,
          osuser,
          username,
          logon_time,
          program,
          module,
          event,
          state,
          sql_id,
          blocking_session
        FROM v\$session
        WHERE type!='BACKGROUND' AND status='ACTIVE' and osuser <> 'www-data'  order by logon_time";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print "<h3>Active SQLs - DW</h3>";
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">SID</th>';
print '<th scope="col">SERIAL#</th>';
print '<th scope="col">OSUSER</th>';
print '<th scope="col">DBUSER</th>';
print '<th scope="col">LOGONTIME</th>';
print '<th scope="col">PROGRAM</th>';
print '<th scope="col">MODULE</th>';
print '<th scope="col">EVENT</th>';
print '<th scope="col">STATE</th>';
print '<th scope="col">SQLID</th>';
print '<th scope="col">BLOCKINGSESSION</th>';
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
