<?php

function arraytoselect($cSelectname, $aArr, $gDefaultvalue = 0, $cExtra = '') {

	$cStr = "<select name=\"$cSelectname\" $cExtra>";
	$cStyle = '';
	$lLabelopen = false;

	foreach ($aArr as $cKey => $cDescr) {

		if ($nPos = strpos($cDescr, ':')) {
		
			$lContinue = false;
			
			switch (substr($cDescr, 0, $nPos)) {
				
				case 'style':
				
					$cStyle = 'style="' . substr($cDescr,$nPos+1) . '" ';
					$lContinue = true;
					break;
				
				case 'color':
				
					$cStyle = 'style="background-color:' . substr($cDescr,$nPos+1) . '" ';
					$lContinue = true;
					break;

				case 'label':
					$cDescr = substr($cDescr, $nPos+1);
					if ($lLabelopen) $cStr .= '</optgroup>';
					$cStr .= "\n<optgroup {$cStyle}label=\"$cDescr\">";
					$lContinue = true;
					$lLabelopen = true;
					break;
			}
				
			if ($lContinue) {	
				continue;
			}
			
		} //only if special parsing needs to happen

		
		$cStr .= "\n<option {$cStyle}value=\"$cKey\" " . trim(selected($gDefaultvalue,$cKey)) . ">$cDescr</option>";
	}

	if ($lLabelopen) $cStr .= '</optgroup>';
	
	$cStr .= "</select>";

	return $cStr;
}

function selected($cVar,$cVal) {
	if ($cVar == $cVal) return ' selected="selected" ';
}


?>
