<?php
$connect = mysqli_connect('dc3-trvl-prod-db-mysql09.dc3.google.zone', 'dba', 'rearden1$', 'taxiway_gateway');
if(mysqli_connect_errno($connect)){
echo 'Failed to connecto to database'.mysqli_connect_error();}


$result= mysqli_query($connect, "SELECT id,Create_Time
                                FROM taxiwaytransactions USE  index(Create_Time)
                                WHERE GDS_NAME = 'Sabre'
                                and Create_Time BETWEEN '2019-02-19 14:00:56' and '2019-02-19 14:26:56';");
                                 
?>
 <table width="500", cellpadding=5 callspacing=5 border=1>
    <tr>
        <th>ID</th>
        <th>Created Time</th>
    </tr>
<?php while($rows = mysqli_fetch_array($result)): ?>
<tr>
    <td><?php echo $rows['id']; ?></td>
    <td><?php echo $rows['Create_Time']; ?></td>
</tr>
<?php endwhile; ?>

</table>
