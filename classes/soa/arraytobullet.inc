<?php
/**
 * Function to take the elements of an array and create an unordered list
 *
 * @param array	$aArr The array to be converted
 * @return mixed Either a <b>string</b> with the unordered list or <b>null</b> if $aArr is empty
 */
function arraytobullet($aArr) {
	
	if(!empty($aArr)) {
		return '<ul><li>' . implode('</li><li>', $aArr) . '</li></ul>';
	} else {
		return null;
	}
	
}
?>