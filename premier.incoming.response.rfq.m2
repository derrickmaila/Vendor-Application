<?php

$data = file_get_contents("php://input");

$info = json_decode($data);

if(!$info) {
	echo json_encode(array("response" => "invaliddata"));
}

$errors = array();

validate($info,$errors);

if($interface == "dev") {
	
} else {
	
}

printresponse($errors);

function validate($data,&$errors) {
	foreach($data as $nLineNumber => $aLine) {
		checkfields($aLine,$nLineNumber,$errors);
	}
}

function checkfield($aFieldvalue,$nLineNumber,&$errors) {
	foreach($aFieldvalue as $key => $value) {
		if($value!=="") {

		} else {
			$errors[$nLineNumber][$key]++;
		}
	}
}
function printeresponse($errorsdata) {
	if(sizeof($errorsdata) == 0) {
		echo json_encode(array("response" => "ok"));
	} else {
		echo json_encode($errorsdata);
	}
}

?>