<?php

$live = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');
$dc = new PDO('mysql:host=m2live-db-ws-002.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliad;charset=utf8', 'root', 'soins009');

/*
Data vendors 
$sql = "SELECT suppliername,supplieraddress,linkcode,suppliercontactname,telephonenumber,faxnumber,legalentityname,legalentitytype,regno,regcountry,emailaddress,website,cellnumber,gpscoord,postaddress,province,districtmunicipality,localmunicipality,financialperiod,annualturnover,vatnumber,seniormgttitle,seniormgtfname,seniormgtsname,seniormgtcommethod,seniormgtcellnumber,seniormgttelnumber,seniormgtemail,salestitle,salescommethod,salesfname,salessname,salescellnumber,salestelnumber,salesemail,admintitle,admincommethod,adminfname,adminsname,admincellnumber,admintelnumber,adminemail,financetitle,financecommethod,financefname,financesname,financecellnumber,financetelnumber,financeemail,supporttitle,supportcommethod,supportfname,supportsname,supportcellnumber,supporttelnumber,supportemail,profiletitle,profilecommethod,profilefname,profilesname,profilecellnumber,profiletelnumber,profileemail,bankname,bankaccnumber,bankbranchname,bankbranchcode,bankacctype,bankaccholdername,businessdscr,businessindustry,businessmarketsegment,beescorecardavail,beesscorecardrating,beeexpirydate,beedaterated,beeexclusioncode,beeexclusionreason,beeagencyname,beeagencynumber,beescoreownership,beescoremgt,beescorequity,beescoreskilldev,beescoreprocurement,beescoreenterprisedev,beescoresociodev,beescoretotal,beescoredeemedtotal,beescoreprocurementlevel,beescoreenterprisetype,beescorevalueadding,beescoreenterprisedevbeneficiary,beescoreparastatal,beescoremultinational,terms,declaration,datacomplete,lastfield,lasttab,shareholders,uploads,businessservices FROM datacollectionvendors WHERE datacomplete > 13";
$statement = $dc->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$rowline = array();
	foreach($row as $col) {
		$col = "'".$col."'";
		$rowline[] = $col;
	}
	$data = implode(",", $rowline);

	$sql = "INSERT INTO applications 
		(suppliername,supplieraddress,linkcode,suppliercontactname,telephonenumber,faxnumber,legalentityname,legalentitytype,regno,regcountry,emailaddress,website,cellnumber,gpscoord,postaddress,province,districtmunicipality,localmunicipality,financialperiod,annualturnover,vatnumber,seniormgttitle,seniormgtfname,seniormgtsname,seniormgtcommethod,seniormgtcellnumber,seniormgttelnumber,seniormgtemail,salestitle,salescommethod,salesfname,salessname,salescellnumber,salestelnumber,salesemail,admintitle,admincommethod,adminfname,adminsname,admincellnumber,admintelnumber,adminemail,financetitle,financecommethod,financefname,financesname,financecellnumber,financetelnumber,financeemail,supporttitle,supportcommethod,supportfname,supportsname,supportcellnumber,supporttelnumber,supportemail,profiletitle,profilecommethod,profilefname,profilesname,profilecellnumber,profiletelnumber,profileemail,bankname,bankaccnumber,bankbranchname,bankbranchcode,bankacctype,bankaccholdername,businessdscr,businessindustry,businessmarketsegment,beescorecardavail,beesscorecardrating,beeexpirydate,beedaterated,beeexclusioncode,beeexclusionreason,beeagencyname,beeagencynumber,beescoreownership,beescoremgt,beescorequity,beescoreskilldev,beescoreprocurement,beescoreenterprisedev,beescoresociodev,beescoretotal,beescoredeemedtotal,beescoreprocurementlevel,beescoreenterprisetype,beescorevalueadding,beescoreenterprisedevbeneficiary,beescoreparastatal,beescoremultinational,terms,declaration,datacomplete,lastfield,lasttab,shareholders,uploads,businessservices)
		VALUES({$data})
	";
	$stmnt = $live->prepare($sql);
	$stmnt->execute();

}
*/
/* Import users 
$sql = "SELECT username,password FROM users";
$statement = $dc->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$sql = "INSERT INTO users (username,password,usertypecontrol) VALUES('{$row->username}','".md5($row->password)."',2)";
	$stmnt = $live->prepare($sql);
	$stmnt->execute();
}
*/

$sql = "SELECT email,datacollectionvendorscontrol, suppliername FROM datacollectionemails LEFT JOIN datacollectionvendors ON(datacollectionemails.datacollectionvendorscontrol = datacollectionvendors.control)";

$statement = $dc->prepare($sql);
$statement->execute();

while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	$sql = "SELECT * FROM users WHERE username = ?";
	$stmnt = $live->prepare($sql);
	$stmnt->execute(array($row->email));

	if($stmnt->rowCount() > 0) {
		$user = $stmnt->fetch(PDO::FETCH_OBJ);
		$update = $live->prepare("UPDATE applications SET usercontrol = ? WHERE suppliername = '{$row->suppliername}'");
		$update->execute(array($user->control));
	}
}



?>