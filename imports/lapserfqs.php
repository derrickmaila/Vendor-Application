<?php
require_once("../classes/amodel.php");
require_once("../models/rfqaudit.php");

$model = new RFQAudit_Model();
$model->connect();

//Get all rfqs with certain status for emergency order
$cSql = "SELECT * FROM rfqheader WHERE ((DATE_ADD(DATE(REQDate), INTERVAL 2 DAY) < NOW() AND RequisitionStatus = 2) 
	OR ((DATE_ADD(DATE(REQDate), INTERVAL 5 DAY) < NOW() AND RequisitionStatus = 1)";

$statement = $model->prepare($cSql);
$statement->execute();
while($row = $model->fetch($statement)) {
	if(!$model->hasBeenSubmitted($row->control)) {
		$aCancellations[] = $row; //Cancel	
	}
}

$ch = curl_init();

$url = "https://soap.m2north.com/premier.incoming.response.rfq.m2?interface=dev"; #TODO change to live on going live

foreach($aCancellations as $aCancellation) {
	//Do curl request to send the cancellation
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($aCancellation));
	$result = curl_exec($ch);
	if($result) {
		//Mark as sent
	} else {
		//Leave for retry
	}
}

curl_close($ch);

?>