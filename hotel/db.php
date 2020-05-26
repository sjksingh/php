<?php
$connect = mysqli_connect('dc3-trvl-prod-db-mysql11.dc3.deem.zone', 'dba', 'rearden1$', 'hotel_db');
if(mysqli_connect_errno($connect)){
echo 'Failed to connecto to database'.mysqli_connect_error();}


$result= mysqli_query($connect, "select host as Host,
                                        db, 
                                        user 
                                 from mysql.db;");
?>
 <table width="500", cellpadding=5 callspacing=5 border=1>
    <tr>
        <th>Host</th>
        <th>DB</th>
        <th>User</th>
    </tr>
<?php while($rows = mysqli_fetch_array($result)): ?>
<tr>
    <td><?php echo $rows['Host']; ?></td>
    <td><?php echo $rows['db']; ?></td>
    <td><?php echo $rows['user']; ?></td>
</tr>
<?php endwhile; ?>

</table>
