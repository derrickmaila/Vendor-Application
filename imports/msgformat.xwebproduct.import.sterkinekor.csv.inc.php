<?php
/**
 *
 * 		Imports Sterkinekor Webproducts, this was copied from the cloud import. This should move products into Sterkinekor database.
 *
 * @author		Dimitriou Androas
 * @date		02 Sept 2013
 *
 * @test		http://brain.m2north.local/engine/engine.catchall.php?cScriptname=clients/sterkinekor/msgformat.xwebproduct.import.sterkinekor.csv.inc.php
 * 			sudo php -f /var/www/m2cloud/brain/engine/engine.catchall.php clients/sterkinekor/msgformat.xwebproduct.import.sterkinekor.csv.inc.php "" normal
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
//	$cFilename = "/var/www/m2cloud/brain/process/clients/sterkinekor/MATMAS.txt";
	$cFilename = "MATMAS.txt";
	
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
			$aLogsys[] = "No Sales Org. on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[1])){
			$aLogsys[] = "No Distribution Channel on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[2])){
			$aLogsys[] = "No Material on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[3])){
			$aLogsys[] = "No Material number on Line ".$nLine." SKIPPING";
			continue;
		}
		
		if (empty($aLine[5])){
			$aLogsys[] = "No Material Type on Line ".$nLine." SKIPPING";
			continue;
		}
		
		/*if (empty($aLine[6])){ //TODO find out if this is a compulsory field or not.
			$aLogsys[] = "No EAN/UPC on Line ".$nLine." SKIPPING";
			continue;
		}*/
		
		if (empty($aLine[7])){
			$aLogsys[] = "No Plant on Line ".$nLine." SKIPPING";
			continue;
		}
		
		#
		#	SALES ORG
		#
		
		if (0 == $oSterkinekor->checksalesorg($aLine[0])){
			
			if (false == $oSterkinekor->addsalesorganisation($aLine[0])){
				$aLogsys[] = 'Coulndt add Sales Org '.$aLine[0]. " SKIPPING";
				continue;
			}
			$aProduct['SOrg'] = $oSterkinekor->getsalesorganisationcontrol($aLine[0]);
		} else {
			$aProduct['SOrg'] = $oSterkinekor->getsalesorganisationcontrol($aLine[0]);
		}
		
		#
		#	MATERIAL TYPE
		#
		
		if (0 == $oSterkinekor->checkmaterialtype($aLine[5])){
			if (false == $oSterkinekor->addmaterialtype($aLine[5])){
				
				$aLogsys[] = 'Coulndt add Materil Type '.$aLine[5]. " SKIPPING".mysql_error();;
				continue;
			}
			$aProduct['MTyp'] = $oSterkinekor->getmaterialtypecontrol($aLine[5]);
		} else {
			$aProduct['MTyp'] = $oSterkinekor->getmaterialtypecontrol($aLine[5]);
		}
		
		#
		#	PLANT
		#
		
		if (0 == $oSterkinekor->checkplant($aLine[7])){
			if (false == $oSterkinekor->addplant($aLine[7])){
		
				$aLogsys[] = 'Coulndt add Plant '.$aLine[7]. " SKIPPING".mysql_error();;
				continue;
			}
			$aProduct['Plant'] = $oSterkinekor->getplantcontrol($aLine[7]);
		} else {
			$aProduct['Plant'] = $oSterkinekor->getplantcontrol($aLine[7]);
		}
		
		#
		#	PRODUCT
		#
		
		if (0 == $oSterkinekor->checkproduct($aLine[2])){
			if (FALSE == $oSterkinekor->createproduct($aLine[2], $aLine[3], $aProduct['SOrg'], $aLine[4],$aProduct['MTyp'], $aLine[6], $aProduct['Plant'])){
				echo "Product didnt save! ".mysql_error()."\n";
			}
		} else {
			$nProduccontrol = $oSterkinekor->getproductcontrol($aLine[2]);
			if (0 == $oSterkinekor->updateproduct($nProduccontrol, $aLine[2], $aLine[3], $aProduct['SOrg'], $aLine[4],$aProduct['MTyp'], $aLine[6], $aProduct['Plant'])){
				$aLogsys[] = "Failed to add product".mysql_error();
				break;
			}
		}
		
		#
		#	DISTRIBUTION CHANNEL
		#
		
		if (0 == $oSterkinekor->checkdistributionchannel($aLine[1])){
			//Add the channel
			$oSterkinekor->adddistributionchannel($aLine[1]);
			
			//Make sure a link doens't exist
			$nDistributionchannelcontrol = $oSterkinekor->getdistributionchannelcontrol($aLine[1]);
			$nProductcontrol = $oSterkinekor->getproductcontrol($aLine[2]);
			if (0 == $oSterkinekor->checkdistributionchannellink($nDistributionchannelcontrol, $nProductcontrol)){
				$oSterkinekor->adddistributionchannellink($nDistributionchannelcontrol, $nProductcontrol);
			}
			
		} else {
		$nDistributionchannelcontrol = $oSterkinekor->getdistributionchannelcontrol($aLine[1]);
		$nProductcontrol = $oSterkinekor->getproductcontrol($aLine[2]);
			if (0 == $oSterkinekor->checkdistributionchannellink($nDistributionchannelcontrol, $nProductcontrol)){
				$oSterkinekor->adddistributionchannellink($nDistributionchannelcontrol, $nProductcontrol);
			}
		}
	}
	
	
	
} while (0);

if (!empty($aLogsys) and is_array($aLogsys)){
	echo "Logsys \n";
	print_r($aLogsys);
}
