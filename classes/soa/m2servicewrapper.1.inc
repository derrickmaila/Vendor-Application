<?php

require_once 'm2clientservice.inc';

class Mtomattachment {
	
	private $contentType;
	private $fileName;
	private $content;
	private $md5Hash;
	
	function __construct($cFilename, $cContentype, $cDatasource) {

		$this->fileName = $cFilename;
		$this->contentType = $cContentype;
		
		if (file_exists($cDatasource)) {			
			$this->md5Hash = md5_file($cDatasource);
			$this->content = file_get_contents($cDatasource);
		} else {
			$this->md5Hash = md5($cDatasource);
			$this->content = $cDatasource;
		}
	}
	
	function getContent() {
		return $this->content;
	} 
	
	function getFileName() {
		return $this->fileName;
	}
	
	function getContentType() {
		return $this->contentType;
	}
	
	function getMd5Hash() {
		return $this->md5Hash;
	}
}

abstract class M2ServiceWrapper {
	
	private $oService = null;
	
	function __construct($cEndpoint, $cUsername, $cPassword) {
		
		$this->oService = new m2Clientservice($cEndpoint, $cUsername, $cPassword);
	}
	
	function getattachments() {
		return $this->oService->getattachments();
	}
	
	function cleanattachments() {
		return $this->oService->cleanattachments();
	}
	
	/**
	 * Create an mtom attachment object
	 * 
	 * @param unknown $cFilename
	 * @param unknown $cContentype
	 * @param unknown $cDatasource (path to file, or string content)
	 * @return Mtomattachment
	 */
	function createattachment($cFilename, $cContentype, $cDatasource) {
		return new Mtomattachment($cFilename, $cContentype, $cDatasource);
	}
	
	protected function call($cMethod, $aParam = array()) {

		return $this->oService->call(
				$aTmp = array(
						'service' => $this->getservicename(),
						'method' => $cMethod,
						'param' => $aParam
				)
		);
	}
	
	function errorget() {
		return $this->oService->errorget();
	}
	
	protected abstract function getservicename(); //each extending class must specify the corresponding service name.	
}

?>