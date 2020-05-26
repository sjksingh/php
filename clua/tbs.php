<?php
// connect to Oracle..
require("/var/www/html/db/zk/connect_clua.php");

if (!$conn) {
$m = oci_error();
trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

// display the header.....
echo "<img valgin='bottom' src='https://static1.squarespace.com/static/589a5e8286e6c034c4ad55fe/t/58dae19e6a49639d84fb005a/1500064110650/?format=1500w' width=10% height=10%  alt='Deem' /><font color='blue' size=6> Production $db_name Environment</font></td></tr></table>
";

$sql = "
SELECT
   a.tablespace_name,
   round(a.bytes_alloc/(1024*1024*1024),1) ,
   round(a.physical_bytes/(1024*1024*1024),1) ,
   round(nvl(b.tot_used,0)/(1024*1024*1024),1) ,
   round((nvl(b.tot_used,0)/a.bytes_alloc)*100,1)
from
   (select
      tablespace_name,
      sum(bytes) physical_bytes,
      sum(decode(autoextensible,'NO',bytes,'YES',maxbytes)) bytes_alloc
    from
      dba_data_files
    group by
      tablespace_name ) a,
   (select
      tablespace_name,
      sum(bytes) tot_used
    from
      dba_segments
    group by
      tablespace_name ) b
where
   a.tablespace_name = b.tablespace_name (+)
and
   a.tablespace_name not in
   (select distinct
       tablespace_name
    from
       dba_temp_files)
and
   a.tablespace_name not like 'UNDO%'
   and a.tablespace_name not like 'USER%'
   and a.tablespace_name not like 'GOLDEN%'
   and (nvl(b.tot_used,0)/a.bytes_alloc)*100 > 10
order by 1
";

$stid = oci_parse($conn, $sql);
oci_execute($stid);

//print '<td></td>';
echo "<center><b><h3>TableSpace Usage Report from $db_name running on $host </h3></b></center>";
echo "<b>Today is " . date("M/d/Y") . "</b><br>";
echo "<br>";
print '<table border="1">';
print '<tr>';
print '<th bgcolor="YELLOW" scope="col">TABLESPACE_NAME</th>';
print '<th bgcolor="YELLOW" scope="col">Total Allocation(GB)</th>';
print '<th bgcolor="YELLOW" scope="col">Total Physcial Allocation(GB)</th>';
print '<th bgcolor="YELLOW" scope="col">Total Used (GB)</th>';
print '<th bgcolor="YELLOW" scope="col">% Used (GB)</th>';
print '</tr>';

// Fetch and print all the records: #3
$color = "";
while ($row = oci_fetch_array($stid)) {

  // Also if TBS usage is > 90% than TBS name should text in RED.

if ($row[4] >= 95 and $row[4] <= 99) $color = 'RED';
 elseif ($row[4] >= 80 and $row[4] <= 90) $color = 'Green';
 //elseif ($row[4] >= 20) $color = 'Green';

echo "<tr>
          <td><b><span style=\"color: $color\">  $row[0] </td></span></b>
          <td> $row[1]  </td>
          <td> $row[2]  </td>
          <td> $row[3]  </td>
          <td><b><span style=\"color: $color\">  $row[4]  </td></span></b>
      </tr>";
 }

oci_free_statement($stid);
oci_close($conn);

// working old....Fetch each row in an associative array
//while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC))
//{print '<tr>';
//  foreach ($row as $item) {
//     print '<td>'.($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp').'</td>';
//}

 //print '</tr>';
//}
//print '</table>';
//oci_free_statement($stid);
//oci_close($conn);
//END
?>
