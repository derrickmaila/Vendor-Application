<?php
require_once('../classes/PHPMailer/class.phpmailer.php');

$db = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');

$sql = "SELECT 
			accountcode,
			suppliername,
			supplieraddress,
			linkcode,
			suppliercontactname,
			telephonenumber,
			faxnumber,
			legalentityname,
			legalentitytype,
			regno,
			regcountry,
			emailaddress,
			website,
			cellnumber,
			postaddress,
			province,
			districtmunicipality,
			localmunicipality,
			financialperiod,
			annualturnover,
			vatnumber,
			seniormgttitle,
			seniormgtfname,
			seniormgtsname,
			seniormgtcommethod,
			seniormgtcellnumber,
			seniormgttelnumber,
			seniormgtemail,
			salestitle,
			salescommethod,
			salesfname,
			salessname,
			salescellnumber,
			salestelnumber,
			salesemail,
			admintitle,
			admincommethod,
			adminfname,
			adminsname,
			admincellnumber,
			admintelnumber,
			adminemail,
			financetitle,
			financecommethod,
			financefname,
			financesname,
			financecellnumber,
			financetelnumber,
			financeemail,
			supporttitle,
			supportcommethod,
			supportfname,
			supportsname,
			supportcellnumber,
			supporttelnumber,
			supportemail,
			profiletitle,
			profilecommethod,
			profilefname,
			profilesname,
			profilecellnumber,
			profiletelnumber,
			profileemail,
			businessdscr,
			businessindustry,
			businessmarketsegment,
			beescorecardavail,
			beesscorecardrating,
			beeexpirydate,
			beedaterated,
			beeexclusioncode,
			beeexclusionreason,
			beeagencyname,
			beeagencynumber,
			beescoreownership,
			beescoremgt,
			beescorequity,
			beescoreskilldev,
			beescoreprocurement,
			beescoreenterprisedev,
			beescoresociodev,
			beescoretotal,
			beescoredeemedtotal,
			beescoreprocurementlevel,
			beescoreenterprisetype,
			beescorevalueadding,
			beescoreenterprisedevbeneficiary,
			beescoreparastatal,
			beescoremultinational
			

	    FROM
	    	applications 
	    LEFT JOIN 
	    	audit 
	    ON(applications.control = audit.control) 
	    	WHERE 
	    datacomplete > 95 
	    	AND 
	    (audit.applicationstagecontrol = 7 OR audit.applicationstagecontrol IS NULL)";


$statement = $db->prepare($sql);
$statement->execute();

function clear($str) {
	return str_replace("\n", " ", $str);
}

$data = "CF_Suppl_ACCOUNT|CF suppl NAME|CF suppl BS_ILIAD_PHYSADDR|CF suppl BS_ILIAD_LINKCODE|CM_cntct |CF suppl PHONE1|CF suppl FAX1|New Field|CF suppl BS_ILIAD_LEGALENTITY|CF suppl BS_ILIAD_COMPANYREG|New Field|CF suppl EMAIL|CRM String|CM_cntct PHONE|CF suppl ADDRESS|New Field|New Field|New Field|CRM String|New Field|CF suppl VATNO|CM_cntct TITLE|CM_cntct FIRSTNAM|CM_cntct SURNAME|CRM Codes|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|CM_cntct TITLE|CRM Codes|CM_cntct FIRSTNAM|CM_cntct SURNAME|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|CM_cntct TITLE|CRM Codes|CM_cntct FIRSTNAM|CM_cntct SURNAME|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|CM_cntct TITLE|CRM Codes|CM_cntct FIRSTNAM|CM_cntct SURNAME|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|CM_cntct TITLE|CRM Codes|CM_cntct FIRSTNAM|CM_cntct SURNAME|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|CM_cntct TITLE|CRM Codes|CM_cntct FIRSTNAM|CM_cntct SURNAME|CM_cntct PHONE_3|CM_cntct PHONE|CM_cntct EMAIL|Not to be imported|Not to be imported|Not to be imported|Not to be imported|Not to be imported|Not to be imported|CRM String|CM_cntct BUSINESSTYPE|CRM String|New Field|CF suppl BS_ILIAD_BBBBEELEVEL|CF suppl BS_ILIAD_EXPITYDATE|New Field|CF suppl BS_ILIAD_EXCLUSIONCD|CF suppl BS_ILIAD_EXCLUSIONRN|New Field|New Field|CF suppl BS_ILIAD_BLACKOWNER|New Field|New Field|New Field|New Field|New Field|New Field|New Field|New Field|New Field|New Field|CF suppl BS_ILIAD_VALUEADDVEN|New Field|New Field|New Field\n";

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth = TRUE;
$mail->Host = 'smtp.dotnetwork2.co.za';
$mail->SMTPDebug = 2;
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->Username = 'vendor.portal@iliadafrica.co.za';
$mail->Password = 'Iliad007';

$mail->FromName = "Premier Portal";
$mail->From = "vendor.portal@iliadafrica.co.za";
$mail->IsHTML( true );

$mail->Subject = "M2North Vendor Details Update Import";
$mail->Body = "See attached";



while($row = $statement->fetch(PDO::FETCH_OBJ)) {
	
	$shareholders = json_decode($row->shareholders);
	unset($row->shareholders);
	$row = (array)$row;
	foreach($shareholders as $nIndex=>$shareholder) {
		foreach($shareholder as $column) {
			
			$row[] = $column;
		}
	}
	foreach($row as &$col) {
		$col = clear($col);
		$col = '"'.$col.'"';
	}

	$rows[] = $row;
	$data.= implode("|",$row)."\n";
}
file_put_contents("output.csv", $data);

$mail->AddAddress("mark.arrow@premier.co.za");
$mail->AddAddress("geromeg@m2north.com");
$mail->AddAddress("aynsleyh@m2north.com");
$mail->AddAddress("vendors@premier.co.za");
$mail->AddAttachment("/var/www/iliadportalv2/imports/output.csv");

if($mail->Send()) {
	echo "Successfully Sent\n";
} else {
	echo "Send Failure\n";
}

/*
85.10.215.135
username : 
password : Q76P7n61H

'CF suppl NAME'|'CF suppl BS_ILIAD_PHYSADDR'|'CF suppl BS_ILIAD_LINKCODE	CM_cntct 	CF suppl PHONE1	CF suppl FAX1	New Field	CF suppl BS_ILIAD_LEGALENTITY	CF suppl BS_ILIAD_COMPANYREG	New Field	CF suppl EMAIL	CRM String	CM_cntct PHONE	Not Applicable	CF suppl ADDRESS	New Field	New Field	New Field	CRM String	New Field	CF suppl VATNO	CM_cntct TITLE	CM_cntct FIRSTNAM	CM_cntct SURNAME	CRM Codes	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	CM_cntct TITLE	CRM Codes	CM_cntct FIRSTNAM	CM_cntct SURNAME	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	CM_cntct TITLE	CRM Codes	CM_cntct FIRSTNAM	CM_cntct SURNAME	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	CM_cntct TITLE	CRM Codes	CM_cntct FIRSTNAM	CM_cntct SURNAME	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	CM_cntct TITLE	CRM Codes	CM_cntct FIRSTNAM	CM_cntct SURNAME	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	CM_cntct TITLE	CRM Codes	CM_cntct FIRSTNAM	CM_cntct SURNAME	CM_cntct PHONE_3	CM_cntct PHONE	CM_cntct EMAIL	Not to be imported	Not to be imported	Not to be imported	Not to be imported	Not to be imported	Not to be imported	CRM String	CM_cntct BUSINESSTYPE	CRM String	New Field	CF suppl BS_ILIAD_BBBBEELEVEL	CF suppl BS_ILIAD_EXPITYDATE	New Field	CF suppl BS_ILIAD_EXCLUSIONCD	CF suppl BS_ILIAD_EXCLUSIONRN	New Field	New Field	CF suppl BS_ILIAD_BLACKOWNER	New Field	New Field	New Field	New Field	New Field	New Field	New Field	New Field	New Field	New Field	CF suppl BS_ILIAD_VALUEADDVEN	New Field	New Field	New Field	Not to be imported	Not to be imported	Not to be imported	Not to be imported	Not to be imported


 */


?>
