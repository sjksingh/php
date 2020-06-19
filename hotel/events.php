<?php
$connect = mysqli_connect('dc3-trvl-prod-db-mysql10.dc3.google.zone', 'dba', 'rearden1$', 'expense');
if(mysqli_connect_errno($connect)){
echo 'Failed to connecto to database'.mysqli_connect_error();}


$result= mysqli_query($connect, "select now() as date,
                                        (count_star/(select sum(count_star) FROM performance_schema.events_statements_summary_by_digest) * 100) as pct, 
                                        count_star, 
                                        left(digest_text,150) as stmt, 
                                        digest
                                 from performance_schema.events_statements_summary_by_digest order by 2 desc;");
?>
 <table width="500", cellpadding=5 callspacing=5 border=1>
    <tr>
        <th>Date</th>
        <th>PCT</th>
        <th>COUNT</th>
        <th>SQL</th>
        <th>SQLID</th>
    </tr>
<?php while($rows = mysqli_fetch_array($result)): ?>
<tr>
    <td><?php echo $rows['date']; ?></td>
    <td><?php echo $rows['pct']; ?></td>
    <td><?php echo $rows['count_star']; ?></td>
    <td><?php echo $rows['stmt']; ?></td>
    <td><?php echo $rows['digest']; ?></td>
</tr>
<?php endwhile; ?>

</table>
