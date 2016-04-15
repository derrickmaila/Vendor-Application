<?php
/**
 *
 * 		Imports Sterkinekor prices, this was copied from the cloud import. This should move products into Sterkinekor database.
 *
 * @author		Dimitriou Androas
 * @date		02 Sept 2013
 *
 * @test		http://brain.m2north.local/engine/engine.catchall.php?cScriptname=clients/sterkinekor/msgformat.xprice.import.sterkinekor.csv.inc.php
 * 			sudo php -f /var/www/m2cloud/brain/engine/engine.catchall.php clients/sterkinekor/msgformat.xprice.import.sterkinekor.csv.inc.php "" normal
 */


//TODO INSERT THE REUIQRES HERE

require_once 'sterkinekor.php';

ini_set('memory_limit','1024M'); //Maximum amount of memory a script may consume
ini_set('max_execution_time','30000'); //Maximum execution time of each script, in seconds
ini_set('max_input_time','3600'); //Maximum amount of time each script may spend parsing request data

$aLogsys = array();
$aStat = array('product' => 0, 'productfavourite' => 0); //TODO LETS MAKE SURE WE NEED THIS.
$nTimestamp = time();
$nLine = 0;

$oSterkinekor = new Sterkinekor();

do {
	
	$aHeader = array();
	
	if (M2_BUCKET == 'm2live') {
		$aHeader['h_custsystemcode'] = 'sterkinekor';
		$aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
		$cDomain = 'sterkinekor.co.za'; //TODO Make sure this is correct
	} else {
		$aHeader['h_custsystemcode'] = 'sterkinekor';
		$aHeader['h_custaccountsystemcode'] = 'sterkinekor.jhb';
		$cDomain = 'sterkinekor.local'; //TODO Make sure we need this
	}
	
	//TODO switch this to op a msgin, and not an fopen
	$cFilename = "PRICNG.txt";
	
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
			/*if (true !== checkheadings($aLine, $aLogsys)){
				break 2;
			}*/ //TODO I need to figure out why this one isn't working.
			continue;
		}
		
		#
		#	CHECK THAT THE FIELDS ARE POPULATED
		#
		
		if (empty($aLine[0])){
			$aLogsys[] = "No Condition Record Number on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[1])){
			$aLogsys[] = "No Material Code on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[2])){
			$aLogsys[] = "No Material Description on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[3])){
			$aLogsys[] = "No amount on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[4])){
			$aLogsys[] = "No Valid From Date on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[5])){
			$aLogsys[] = "No Valid To Date on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (0 == $oSterkinekor->checkproductprice($aLine[0])){
			$nProductcontrol = $oSterkinekor->getproductcontrol($aLine[1]);
			$nDstcontrol = $oSterkinekor->getdstcontrol($aLine[7]);
			if (FALSE == $oSterkinekor->createproductprice($aLine[0], $nProductcontrol, $aLine[3], $aLine[4], $aLine[5],$aLine[6],$nDstcontrol) ){
				$aLogsys[] = "Failed to create product price".mysql_error();
			}
		} else {
			$aLogsys[] = "Price already exists - ".$aLine[0];
		}
		
		//TODO remove this safety break;
		$nLine++;
		//if ($nLine > 20 ) break; //TODO this is to be removed later
	}
	
	
	
} while (0);

if (!empty($aLogsys) and is_array($aLogsys)){
	echo "Logsys \n";
	print_r($aLogsys);
}
