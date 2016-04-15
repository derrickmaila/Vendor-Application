<?php

//List of standard functions that apply to most classes

require_once 'arraytobullet.inc';

class m2Std {


var $lError = false;
var $aError = array();


//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

function errorset($cMsg, $cCode = '') {
	$this->aError[] = $cMsg;
	$this->lError = true;
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

function iserror() {
	return $this->lError;
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

function errorget($aParam = array()) {
	return arraytobullet($this->aError);
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

//RF 14 june 12: added this function to reduce class code used to check for passed parameters, not that it checks for existence and not value
function checkforfields($aParam, $aRequiredfields, &$aError) {
	
	$nMissing = 0;
	foreach ($aRequiredfields as $cField) {
		if (!isset($aParam[$cField])) {
			$aError[] = "Missing '$cField'";
			$nMissing++;
		}
	}
	
	if ($nMissing > 0) {
		return false;
	} else {
		return true;
	}
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=--=-=-==---=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-

} //class m2Std


?>