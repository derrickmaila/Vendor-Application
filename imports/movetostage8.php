<?php

/**

	Migrating the datacollection data to stage 8 for the portal

*/


$db = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');

$sql = "SELECT *,applications.usercontrol as usercontrol,applications.control as applicationcontrol FROM applications LEFT JOIN audit ON(applications.control = audit.applicationcontrol) WHERE datacomplete = 100 AND audit.applicationcontrol IS NULL";
$statement = $db->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$route = explode(",", $row->businessservices);
	$route = $route[0];

	$sql = "SELECT *,users.control as usercontrol FROM categoryrouting LEFT JOIN users ON(categoryrouting.email = users.username) WHERE categoryrouting.description LIKE '%{$route}%'";
	$routing = $db->prepare($sql);
	$routing->execute();
	$routeuser = $routing->fetch(PDO::FETCH_OBJ);
	
	$routeuser = $routeuser->usercontrol;

	$sql = "INSERT INTO 
				audit 
			(usercontrol,datelogged,applicationcontrol,applicationstagecontrol,applicationtypemarker,active,userassignedtocontrol,currentusercontrol) 
			VALUES(?,?,?,?,?,?,?,?)";


	$data = array(
		$row->usercontrol,date("Y-m-d H:i:s"), $row->applicationcontrol, 6, 'new', 1, $routeuser, $routeuser
	);
	print_r($data);
	$st = $db->prepare($sql);
	$st->execute($data);

}

?>