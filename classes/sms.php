<?php
class SMS_Class {
	public function send($number = "",$message = "") {
		if(strlen($number) > 9 && strlen($number) < 14) {
		 $mailTo = $number."@2way.co.za";
         $Subject = "dabhelp";
         $mailHeaders = "From: andre@dabhand.co.za\n";
         mail($mailTo, $Subject, $message, $mailHeaders);
         return true;
		} else {
			return false;
		}
		
	}
}

?>