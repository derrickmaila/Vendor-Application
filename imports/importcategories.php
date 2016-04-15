<?php
/**
	@author Gerome Guilfoyle
	@date 3 Jan 2013
	@description
		To import categories

*/

$cFile = "categories.csv";

$db = new PDO('mysql:host=beta;dbname=iliad;charset=utf8', 'skeuser', 'ske!@#');

$file = fopen($cFile,"r");
$count = 0;

while($line = fgetcsv($file)) {
	if($count == 0) {
		$count++;
		continue;
	}
	
	$description = $line[0]; 
	$id = $line[1];
	$name = $line[2];
	$email = $line[3];


	$sql = "INSERT INTO 
				categoryrouting
			(description,id,name,email) 
			VALUES(?,?,?,?)";
	$statement = $db->prepare($sql);
	$data = array(
			$description,$id,$name,$email

	);
	$statement->execute($data);


}



?>