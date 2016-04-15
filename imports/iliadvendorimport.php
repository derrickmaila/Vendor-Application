<?php
/**
	@author Gerome Guilfoyle
	@date 3 Jan 2013
	@description
		To import partial vendor data into our database for use with our
		data collection project

*/

$cFile = "vendordata.csv";

$db = new PDO('mysql:host=beta;dbname=iliad;charset=utf8', 'skeuser', 'ske!@#');

$file = fopen($cFile,"r");
$count = 0;

while($line = fgetcsv($file)) {
	if($count == 0) {
		$count++;
		continue;
	}
	$address = array();

	$linkcode = $line[0];
	$suppliername = $line[1];
	$address[] = getpopulatedline($line[2]);
	$address[] = getpopulatedline($line[3]);
	$address[] = getpopulatedline($line[4]);
	$address[] = getpopulatedline($line[5]);

	$contactname = getpopulatedline($line[6]);

	$cAddress = implode("\n", $address);

	$telephonenumber = $line[7];
	if(!$telephonenumber) {
		$telephonenumber = $line[8];
	}


	$faxnumber = $line[9];
	if(!$faxnumber) {
		$faxnumber = $line[10];
	}
	$emails = explode(";",trim($line[20]));
	$sql = "INSERT INTO 
				datacollectionvendors 
			(suppliername,supplieraddress,linkcode,suppliercontactname,telephonenumber,faxnumber) 
			VALUES(?,?,?,?,?,?)";
	$statement = $db->prepare($sql);
	$data = array(
			$suppliername,
			$cAddress,
			$linkcode,
			$contactname,
			$telephonenumber,
			$faxnumber

	);
	$statement->execute($data);

	$vendorcontrol = $db->lastInsertId();
	foreach($emails as $email) {
		$sql = "INSERT INTO datacollectionemails (email,datacollectionvendorscontrol) VALUES(?,?)";
		$statement = $db->prepare($sql);
		$statement->execute(array($email,$vendorcontrol));

		$uniquecode = sha1($email . 'doYouLikeSaudfhfiushf78h4897htr9834r8j43r89h43r374fh9834uce');

		$sql = "INSERT INTO datacollectionuniquecodes (email,code) VALUES(?,?)";
		$statement = $db->prepare($sql);
		$statement->execute(array($email,$uniquecode));

	}

}

function getpopulatedline($cLine) {
	if(trim($cLine) == "") return;
	else return $cLine;
}


?>