<?php
$username = "dba";
$password = "rearden1$";
$servers = array(
	"dc3-trvl-prod-db-mysql110.dc3.google.zone",
	"dc3-trvl-prod-db-mysql111.dc3.google.zone",
	"dc3-trvl-prod-db-mysql119.dc3.google.zone",
);

$errors = '';

foreach($servers as $server) {
	$link = mysqli_connect($server, $username, $password);
	if($link) {
		$res = mysqli_query("SHOW SLAVE STATUS", $link);
		$row = mysqli_fetch_field($res);
		if($row['Slave_IO_Running'] == 'No') {
			$errors .= "Slave IO not running on $server\n";
			$errors .= "Error number: {$row['Last_IO_Errno']}\n";
			$errors .= "Error message: {$row['Last_IO_Error']}\n\n";
		}
		if($row['Slave_SQL_Running'] == 'No') {
			$errors .= "Slave SQL not running on $server\n";
			$errors .= "Error number: {$row['Last_SQL_Errno']}\n";
			$errors .= "Error message: {$row['Last_SQL_Error']}\n\n";
		}
		mysqli_close($link);
	}
	else {
		$errors .= "Could not connect to $server\n\n";
	}
}

if($errors) {
	mail('[email address]', 'MySQL slave errors', $errors);
}
?>
