<?php
$db = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');

//$sql = "SELECT * FROM applications WHERE mainservice is NULL";
$sql = "SELECT * FROM applications WHERE mainservice IS NULL or mainservice = ''";

$statement = $db->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$sql = "UPDATE applications SET mainservice = ? WHERE control = ?";
	$mainservice = explode(",",$row->businessservices);
	echo "Updating {$row->control}\n";
	$stat = $db->prepare($sql);
	if(!$stat->execute(array($mainservice[0],$row->control))) {
		echo "Failed to update";	
	}
}

?>
