<?php

// Create connection to Oracle
$username = "system";
$password = "change_me";
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.50.96.67)(PORT = 1521)))(CONNECT_DATA=(SID = club)))";

$conn = oci_connect( $username, $password, $db);
if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$sql = "
SELECT 
command_type,
PARSING_SCHEMA_NAME,
sql_id,
module,
to_char(LAST_ACTIVE_TIME,'mm/dd hh:mi:ss am')Time,
executions,
sql_text
FROM v\$sqlarea where PARSING_SCHEMA_NAME NOT IN ('SYS','SYSTEM') 
and command_type IN (3,2,6,7)
and
last_active_time >
sysdate-10/1440 -- 10 minutes
order by Last_Active_Time desc
";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - Linux-CLUB</h3>';
print '<h3>SQL Executed in last 10 minutes...</h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">COMMAND</th>';
print '<th scope="col">SCHEMANAME</th>';
print '<th scope="col">SQLID</th>';
print '<th scope="col">MODULE</th>';
print '<th scope="col">RUNTIME</th>';
print '<th scope="col">EXECUTIONS</th>';
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
