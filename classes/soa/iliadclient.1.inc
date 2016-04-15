<?php

require_once 'm2servicewrapper.1.inc';

class Iliad01Client extends M2ServiceWrapper {
	
	/**
	 * Submit an export file to the relevant routes.
	 * 
	 * @param unknown $cExportprocessname
	 * @param unknown $aMtomattachment
	 * @param unknown $aRoutes
	 * @return boolean
	 */
	function submitMsgout($cExportprocessname, $aMtomattachment, $cRoute) {
	
		if (false === $gResponse = $this->call('submitMsgout', array('exportProcessPath' => $cExportprocessname, 'attachments' => $aMtomattachment, 'route' => $cRoute))) {
			return false;
		}
	
		return $gResponse->return;
	}
	
	/**
	 * Upload a file to virus scan, a report object will be returned, false is returned on failure.
	 * 
	 * @param unknown $oMtomattachment
	 */
	function avScan($oMtomattachment) {
	
		if (false === $gResponse = $this->call('avScan', array('attachment' => $oMtomattachment))) {
			return false;
		}
	
		return $gResponse->return;
	}
	
	function getservicename() {
		return 'Iliad01';
	}
	
}
	

?>