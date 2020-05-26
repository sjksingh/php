<?php
// auto regresh 5 sec
//$url1=$_SERVER['REQUEST_URI'];
//header("Refresh: 5; URL=$url1");

// Create connection to Oracle
// connect to Oracle..
require("/var/www/html/db/zk/connect_clud.php");

if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

// display the header.....
echo "<img valgin='bottom' src='https://static1.squarespace.com/static/589a5e8286e6c034c4ad55fe/t/58dae19e6a49639d84fb005a/1500064110650/?format=1500w' width=10% height=10%  alt='Deem' /><font color='blue' size=6> Production $db_name Environment</font></td></tr></table>
";

$sql = "select substr(b.module,1,10) module,
       substr(b.username,1,10) username,
       ltrim(rtrim(substr(b.machine,1,10))) machine,
       substr(ltrim(rtrim(a.sql_address)),1,12) sql_address,
       substr(ltrim(rtrim(a.sql_hash_value)),1,12) sql_hash_value ,
       rtrim(ltrim(elapsed_seconds)) esec ,
       ltrim(rtrim(substr(a.opname,1,15))) opname,
       rtrim(ltrim(substr(a.TARGET,1,15))) TARGET,
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
print "<h3>LongOPS Queries - $db_name</h3>";
//print '<h3>LongOPS Report</h3>';
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
