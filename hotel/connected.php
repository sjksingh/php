<?php
$connect = mysqli_connect('dc3-trvl-prod-db-mysql11.dc3.deem.zone', 'dba', 'rearden1$', 'hotel_db');
if(mysqli_connect_errno($connect)){
echo 'Failed to connecto to database'.mysqli_connect_error();}


$result= mysqli_query($connect, "
       SELECT
        COUNT(*) AS conn_count,
        SUBSTRING_INDEX(host,':',1) AS ip,
        user,
        db
    FROM
        INFORMATION_SCHEMA.PROCESSLIST
    GROUP BY
        ip,
        user,
        db
    ORDER BY
        conn_count, ip
    DESC;");
                                 
?>
 <table width="500", cellpadding=5 callspacing=5 border=1>
    <tr>
        <th>Count</th>
        <th>Connected HOST</th>
        <th>USER</th>
        <th>DB</th>
    </tr>
<?php while($rows = mysqli_fetch_array($result)): ?>
<tr>
    <td><?php echo $rows['conn_count']; ?></td>
    <td><?php echo $rows['ip']; ?></td>
    <td><?php echo $rows['user']; ?></td>
    <td><?php echo $rows['db']; ?></td>
</tr>
<?php endwhile; ?>

</table>
