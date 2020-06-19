<?php

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
select s.username, s.sid, s.sql_id, s.blocking_session, s.sql_hash_value, s.machine, s.program, s.last_call_et,
decode(w.event,'latch free',w.event||': '||l.name||' ',w.event) event, w.seconds_in_wait sec_wait, s.state,
decode(w.event,
'db file sequential read',substr(substr(d.name,instr(d.name,'\',-1)+1,length(d.name)),instr(substr(d.name,instr(d.name,'\',-1)+1,length(d.name)),'/',-1)+1,
length(substr(d.name,instr(d.name,'\',-1)+1,length(d.name))))||' '||w.p1text||'='||w.p1||' '||w.p2text||'='||w.p2||' '||w.p3text||'='||w.p3,
'db file scattered read' ,substr(substr(d.name,instr(d.name,'\',-1)+1,length(d.name)),instr(substr(d.name,instr(d.name,'\',-1)+1,length(d.name)),'/',-1)+1,
length(substr(d.name,instr(d.name,'\',-1)+1,length(d.name))))||' '||w.p1text||'='||w.p1||' '||w.p2text||'='||w.p2||' '||w.p3text||'='||w.p3,
w.p1text||'='||w.p1||' '||decode(w.p2text,null,'',w.p2text||'='||w.p2)||' '||decode(w.p3text,null,'',w.p3text||'='||w.p3)
) name, 
o.owner || '.' ||o.object_name wait_object_name
  from
v\$session_wait w,
v\$session s,
v\$datafile d, v\$latch l, v\$process p,
dba_objects o
where w.sid = s.sid and w.p2 = l.latch#(+) and p.addr = s.paddr
and w.event not in ('Idle', 'SQL*Net message from client')
and s.type = 'USER'
and w.p1=d.file#(+)
and s.row_wait_obj# = o.object_id(+)
order by s.sql_hash_value,1  
";  

$stid = oci_parse($conn, $sql);
oci_execute($stid);

// Fetch each row in an associative array
print '<h3>Connected to Production - CLUB</h3>';
print '<h3> System waits (user, non-idle)  </h3>';
print '<table border="1">';
print '<tr>';
//print '<td></td>';
print '<th scope="col">User</th>';
print '<th scope="col">Session ID</th>';
print '<th scope="col">SQL ID</th>';
print '<th scope="col">Blocking Session</th>';
print '<th scope="col">SQL Hash</th>';
print '<th scope="col">Machine</th>';
print '<th scope="col">Program</th>';
print '<th scope="col">Last call ET</th>';
print '<th scope="col">Event Name</th>';
print '<th scope="col">Time Waited</th>';
print '<th scope="col">State</th>';
print '<th scope="col">Event Details</th>';
print '<th scope="col">Object Name</th>';
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
