<?php
require_once("PHPMailer/class.phpmailer.php");
class Mail_Class {
	private $mail;
	
	public function __construct() {
	
		$this->mail = new PHPMailer();

		$this->mail->IsSMTP();
		$this->mail->SMTPAuth = TRUE;
		$this->mail->Host = 'smtp.premier.co.za';
		$this->mail->Port = 587;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->Username = '';
		$this->mail->Password = '';
		$this->mail->IsHTML(true);


		$this->mail->FromName = "Premier Portal";
		$this->mail->From = "vendor.portal@premier.co.za";



	}
	public function sendMail($to,$body,$refno) {

		$this->mail->Body = $body ;

		$this->mail->altBody = strip_tags($body);
		$this->mail->FromName = "PremierVendor Portal";
		$this->mail->From = "vendor.portal@premier.co.za";
		$this->mail->Subject = "Ref no: #".$refno." Premier Portal";
		$this->mail->AddAddress($to);
		#TODO remove when live
//		$this->mail->AddCC("geromeg@m2north.com");
//		$this->mail->AddCC("rudolph.moolman@premier.co.za");

		if(!$this->mail->Send()) throw new Exception("Could not send mail");
	}

	public function formatPost() {
		$html = "<table>";
		foreach($_POST as $index=> $value) {
			$html.="<tr><td>{$index}</td><td>{$value}</td></tr>";
		}
		$html.="</table>";
		return $html;
	}
	public function addAttachment($file) {
		$this->mail->addAttachment($file);
	}
}

?>