<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

require_once("ske.php");





ini_set('memory_limit','1024M'); //Maximum amount of memory a script may consume
ini_set('max_execution_time','30000'); //Maximum execution time of each script, in seconds
ini_set('max_input_time','3600'); //Maximum amount of time each script may spend parsing request data

$aLogsys = array();
$aStat = array('product' => 0, 'productfavourite' => 0); //TODO LETS MAKE SURE WE NEED THIS.
$nTimestamp = time();
$nLine = 0;



do {
	
	$aHeader = array();
	
	/*if (M2_BUCKET == 'm2live') {
		$aHeader['h_custsystemcode'] = 'sterkinekor';
		$aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
		$cDomain = 'sterkinekor.co.za'; //TODO Make sure this is correct
	} else*/ {
		$aHeader['h_custsystemcode'] = 'sterkinekor';
		$aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
		$cDomain = 'sterkinekor.local'; //TODO Make sure we need this
	}
	
	//TODO switch this to op a msgin, and not an fopen
	$cFilename = "DEBMAS.txt";
	
	if (false === ($cHandle = fopen($cFilename, 'r'))){
		$aLogsys[] = "Failed to open the filename";
		break;
	}
	
	if (false === ($cFile = fread($cHandle, filesize($cFilename)))){
		$aLogsys[] = 'Failed to read the line';
		break;
	}
	
	$aFile = explode("\n", $cFile);
	
	
	foreach ($aFile as $nLine => $cLine){
		$aLine = explode("\t", $cLine);
		
		if (sizeof($aLine) == 1) continue; // Skip the empty lines
		
		if ($nLine == 0){
			$aExpected = array(
				'Customer',
				'Name 1',
				'OrBlk',
				'DelF',
				'Funct',
				'Customer',
				'Customer number of business pa',
				'SOrg.',
				'DChl',
				'Dv',
				'Total amount',
				'Crcy',
				'CoCd'

			);


			if (true !== checkheadings($aLine, $aExpected)){
				$aLogsys[] = "Headings do not match";
				break 2;
			}
			continue;
		}

		if($aLine[5] == "") {
			$aLogsys[] = "No ship to code found - SKIP";
			continue;
		}
		if($aLine[8] == "") {
			$aLogsys[] = "No distribution channel found - SKIP";
			continue;
		}

		$aData['name'] = $aLine[1];
		$aData['orderblock'] = $aLine[2];
		$aData['deleted'] = $aLine[3];
		$aData['customerfunctioncontrol'] = 0;
		$aData['shiptocode'] = $aLine[5];
		$aData['shiptoname'] = $aLine[6];
		$aData['salesorganisationcontrol'] = 0;
		$aData['dstchl'] = $aLine[8];
		$aData['customerdivisioncontrol'] = 0;
		$aData['creditlimit'] = $aLine[10];
		$aData['currencycontrol'] = 0;
		$aData['holdingcompanycontrol'] = 0;
		
		$ske = new SKE();
		$ske->createCustomer($aData);

		/*echo "<pre>";
		print_r($aLine);
		echo "</pre>";*/

	}

} while (0);

if (!empty($aLogsys) and is_array($aLogsys)){
	echo "Logsys \n";
	print_r($aLogsys);
}

function checkheadings($aExpected = array(),$aHeadings = array()) {
	return ($aExpected === $aHeadings);
}
function set($value,&$thevalue,&$aLogsys,$message) {
	if($value == "") {
		$aLogsys[] = $message;
	} else {
		$thevalue = $value;
	}
}