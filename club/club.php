<?php

// connect to Oracle..
require("/var/www/html/db/zk/connect_club.php");

if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

// display the header.....
echo "<img valgin='bottom' src='https://static1.squarespace.com/static/589a5e8286e6c034c4ad55fe/t/58dae19e6a49639d84fb005a/1500064110650/?format=1500w' width=10% height=10%  alt='Deem' /><font color='blue' size=6> Production $db_name Environment</font></td></tr></table>
";

$sql = "SELECT
        module,count(1)
        from v\$session
        where module not in ('Streams','KTSJ','apache2@dc3-trvl-prod-db-prcmon01 (TNS V1-V3)','MMON_SLAVE')
        group by module
        order by module";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
echo "<h3>Connected Services - $db_name</h3>";
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">Service Name</th>';
print '<th scope="col">Sessions Count</th>';
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
