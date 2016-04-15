<?php
$db = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');


$sql = "SELECT * FROM applications";
$statement = $db->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$sql = "SELECT * FROM audit WHERE applicationcontrol = ?";
	$stmnt = $db->prepare($sql);
	$stmnt->execute(array($row->control));
	$audit = $stmnt->fetch(PDO::FETCH_OBJ);

	if($stmnt->rowCount() == 0) {//do something fancy and fix the audit
		$sql = "SELECT * FROM categoryrouting WHERE description = '{$row->mainservice}'";
		$cat = $db->prepare($sql);
		
		$cat->execute();
		$category = $cat->fetch(PDO::FETCH_OBJ);
		
		$sql = "SELECT * FROM users WHERE username = '{$category->email}' and usertypecontrol = 1";
		$pm = $db->prepare($sql);
		$pm->execute();
		$procman = $pm->fetch(PDO::FETCH_OBJ);
		//build up the audit
		$sql = "INSERT INTO audit (usercontrol,datelogged,applicationcontrol,
			applicationstagecontrol,notificationsent,applicationtypemarker,previoususercontrol,active,userassignedtocontrol,currentusercontrol)
			VALUES(
				{$row->usercontrol},NOW(),{$row->control},1,1,'new',0,1,{$procman->control},{$row->usercontrol}

			);";
		$stat = $db->prepare($sql);
		$stat->execute();

		echo $sql."\n";
	}
}

?>
