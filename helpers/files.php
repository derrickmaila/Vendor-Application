<?php
function getfileextension($filename) {
	$pos = strpos($filename,".");
	return substr($filename,$pos);
}

?>