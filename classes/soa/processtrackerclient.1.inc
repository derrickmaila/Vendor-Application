<?php 

require_once WS_ROOT . 'shared/class/soa/m2servicewrapper.1.inc';

class ProcessTrackerClient extends M2ServiceWrapper {
	
	private static $lSuccess = null;
	private static $cSummary = null;
	
	/**
	 * Static method that should be called from within processes to update their success status.
	 * 
	 * @param unknown $lSuccess
	 * @param unknown $cSummary
	 */
	static function setsuccess($lSuccess) {
		self::$lSuccess = $lSuccess;			
	}
	
	/**
	 * Get the success status, if not set null will be returned.
	 * 
	 * @return unknown
	 */
	static function getsuccess() {
		return self::$lSuccess;
	}
	
	/**
	 * Static method used within process to add to the process execution summary.
	 * The summary messages should only be added if they add value for a customer, ie auditing info, or something for the client to action.
	 * 
	 * @param unknown $cSummary
	 */
	static function addsummaryline($cSummary) {
		if (self::$cSummary !== null) self::$cSummary .= "\n";
		self::$cSummary .= $cSummary;
	}
	
	/**
	 * Return all added summary lines, if not set null will be returned.
	 * @return Ambigous <string, unknown>
	 */
	static function getsummary() {		
		return self::$cSummary;
	}
	
	/**
	 * Add a job via the process tracker to enable tracking on it.
	 * 
	 * @param unknown $cProcesspath
	 * @param unknown $cParameters
	 * @param unknown $cQueue
	 * @return Ambigous <stdClass, boolean>
	 */
	function submitProcessJob($cProcesspath, $cParameters, $cQueue) {
		
		return $this->call('submitProcessJob', array('processPath' => $cProcesspath, 'parameters' => $cParameters, 'queue' => $cQueue));
	}
	
	/**
	 * Intended for a process that has been called to update its status.
	 * 
	 * @param String $cExecutionid
	 * @param Boolean $lSuccess
	 * @param Integer $nActualruntime
	 * @param String $cSummary
	 * @return Ambigous <stdClass, boolean>
	 */
	function updateExecution($cExecutionid, $lSuccess, $nActualruntime, $cSummary) {
		
		//Override with static vars as they would get set within a process.
		if (self::$lSuccess !== null) $lSuccess = self::$lSuccess;
		if (self::$cSummary !== null) $cSummary = self::$cSummary;
				
		return $this->call('updateExecution', array('executionID' => $cExecutionid, 'success' => $lSuccess, 'actualRuntime' => $nActualruntime, 'summary' => $cSummary));
	}
	
	
	/**
	 * Get details for a process execution.
	 * 
	 * @param unknown $cExecutionid
	 * @return Ambigous <stdClass, boolean>
	 */
	function getExecution($cExecutionid) {
		
		if (false === $gResponse = $this->call('getExecution', array('executionID' => $cExecutionid))) {
			return false;
		}		
		
		if (!is_object($gResponse)) $gResponse = new stdClass();
		
		return $gResponse;
	}
	
	
	/**
	 * Get process executions linked to this msgin.
	 * 
	 * @param unknown $cMsginid
	 * @param unknown $nMsginattachmentid
	 * @return boolean|multitype:|mixed
	 */
	function getExecutionsByLink($cMsginid, $nMsginattachmentid) {
	
		if (false === $gResponse = $this->call('getExecutionsByLink', array('executionLinkRequest' => array('msginID' => $cMsginid, 'msginAttachmentID' => $nMsginattachmentid)))) {
			return false;
		}
		
		if (!$gResponse->return) return array();
	
		return $gResponse->return;
	}
	
	function getservicename() {
		return 'AfricaEdi01';
	}
}

?>