<?php
$DB = new mysqli ('dc3-trvl-prod-db-mysql108.dc3.deem.zone', 'dba', 'rearden1$');
$results = $DB->query('show databases');
$allDbs = array();
while ($row = $results->fetch_array(MYSQLI_NUM))
{
    $allDbs[] = $row[0];
}
$results->close();
foreach ($allDbs as $dbName)
{
    if ($dbName != 'information_schema' && $dbName != 'mysql')
    {
        $DB->select_db($dbName);
        $results = $DB->query('SHOW TABLE STATUS WHERE Data_free > 10 ');
        if ($results->num_rows > 0)
        {
            while ($row = $results->fetch_assoc())
            {
                $DB->query('show table ' . $row['Name']);
            }
        }
        $results->close();
    }
}
$DB->close();
?>
