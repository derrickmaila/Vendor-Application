<?php
$email = "marius.potgieter@premier.co.za";
		$name = substr($email, 0,strpos($email, "@"));
		$aName = explode(".", $name);
		echo ucwords($aName[0])." ".ucwords($aName[1]);
?>