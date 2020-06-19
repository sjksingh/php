<?php
$connect = mysqli_connect('dc3-trvl-prod-db-mysql11.dc3.google.zone', 'dba', 'rearden1$', 'hotel_db');
if(mysqli_connect_errno($connect)){
echo 'Failed to connecto to database'.mysqli_connect_error();}


$result= mysqli_query($connect, "
SELECT digest_text
, count_star
, avg_timer_wait
FROM performance_schema.events_statements_summary_by_digest
ORDER BY avg_timer_wait DESC
LIMIT 5;");
                                 
?>
 <table width="500", cellpadding=5 callspacing=5 border=1>
    <tr>
        <th>SQL</th>
        <th>count_star</th>
        <th>avg_timer_wait</th>
    </tr>
<?php while($rows = mysqli_fetch_array($result)): ?>
<tr>
    <td><?php echo $rows['digest_text']; ?></td>
    <td><?php echo $rows['count_star']; ?></td>
    <td><?php echo $rows['avg_timer_wait']; ?></td>
</tr>
<?php endwhile; ?>

</table>
